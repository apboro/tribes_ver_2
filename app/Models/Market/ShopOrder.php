<?php

namespace App\Models\Market;

use App\Events\BuyProductEvent;
use App\Events\FeedBackAnswer;
use App\Exceptions\Invalid;
use App\Models\Author;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Shop;
use App\Models\TelegramUser;
use Discord\Helpers\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Event;
use Log;

/**
 * @property string $token
 * @property int $telegram_user_id
 */
class ShopOrder extends Model
{
    use HasFactory;

    private const BUYABLE_TITLE = 'Спасибо за покупку!';
    private const BUYABLE_DESCRIPTION = 'Продавец получит ваш заказ и свяжется с вами в ближайшее время';
    private const NOT_BUYABLE_TITLE = 'Вы оформили предзаказ';
    private const NOT_BUYABLE_DESCRIPTION = 'Продавец получит ваш заказ и свяжется с вами в ближайшее время';

    public const TYPE_BUYBLE = 1;
    public const TYPE_NOT_BUYBLE = 2;
    public const TYPE_REJECT = 0;


    public const STATUS_NAME_LIST = [
        0 => 'Отменён',
        1 => 'Покупка',
        2 => 'Предзаказ'
    ];

    public const STATUS_TYPE_TITLE = [
        0 => '',
        1 => self::BUYABLE_TITLE,
        2 => self::NOT_BUYABLE_TITLE,
    ];

    public const STATUS_TYPE_DESCRIPTION = [
        0 => '',
        1 => self::BUYABLE_DESCRIPTION,
        2 => self::NOT_BUYABLE_DESCRIPTION,
    ];

    protected $table = 'shop_orders';

    protected $fillable = [
        'telegram_user_id',
        'delivery_id',
        'shop_id',
    ];

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function telegramMeta(): HasOne
    {
        return $this->hasOne(TelegramUser::class, 'telegram_id', 'telegram_user_id');
    }

    public function author()
    {
        return $this->products->first()->belongsTo(Author::class);
    }

    public function getSellerId(): int
    {
        return $this->products->first()->shop->user_id;
    }

    public static function getOrder(int $id): self
    {
        return self::where('id', $id)->with('products')->first();
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'shop_order_product_list', 'order_id')
            ->withPivot('quantity','price' );
    }

    public function productsWithTrashed(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'shop_order_product_list', 'order_id')
            ->withTrashed()
            ->withPivot('quantity','price');
    }

    public function getFirstProduct(): Product
    {
       return $this->products->first() ?? Invalid::null('products null');
    }

    public function getShop(): Shop
    {
        return $this->getFirstProduct()->getShop();
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
        $this->save();
    }

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(ShopDelivery::class, 'id');
    }

    public static function makeByUser(TelegramUser $tgUser, ShopDelivery $shopDelivery, int $shopId): self
    {
        /** @var ShopOrder $order */
        return self::make($tgUser, $shopDelivery, $shopId);
    }

    public function getPrice(): int
    {
        $price = 0;

        foreach ($this->products as $product) {
            $price += $product->pivot->price * $product->pivot->quantity;
        }

        return $price;
    }

    public static function make(TelegramUser $user, ShopDelivery $shopDelivery, int $shopId): self
    {
        return self::create([
            'telegram_user_id' => $user->telegram_id,
            'delivery_id'      => $shopDelivery->id,
            'shop_id'          => $shopId
        ]);
    }

    /**
     * @see \App\Services\Pay\PayReceiveService::actionAfterPayment
     * @param Payment $payment
     *
     * @return void
     */
    public static function actionAfterPayment(Payment $payment)
    {
        /** @var self $self */
        $self = $payment->payable()->first();

        $bayerUser = TelegramUser::findByTelegramId($self->telegram_user_id);

        $messageForOwner = self::prepareMessageToOwner($self, null);
        $messageForBayer = self::prepareMessageToBayer($self);

        Event::dispatch(new BuyProductEvent($messageForOwner, $self->products->first()->shop->user));
        Event::dispatch(new BuyProductEvent($messageForBayer, $bayerUser->user));
    }

    public static function prepareMessageToOwner(self $self, string $email): string
    {
        $orders = self::getOrdersStringList($self);
        $currentEmail = $self->delivery->email;
        if($currentEmail !== $email) {
            $currentEmail = $email;
        }

        $phone = $self->delivery->phone;
        $phoneString = $phone ? '   - телефон: ' . $phone . "\n": '';
        $userName = $self->telegramMeta->user_name ?? '';

        $tagA = '<a href="http://t.me/'. $userName . '">'. $userName . '</a>';
        //TODO  <кол-во товара>
       $message = '<b> Оформлен заказ № ' . $self->id . '</b>' . "\n"
        . 'Контакты покупателя:' . "\n"
        . '   - телеграм: ' . $tagA . "\n"
        .  $phoneString
        . '   - почта: ' . $currentEmail . "\n"
        . '   - адрес доставки:'. "\n"
        .  $self->delivery->address . "\n"
        .  "\n"
        . 'Содержимое заказа:' . "\n"
        .  $orders
        .  "\n"
        . 'На сумму: ' . $self->getPrice() . ' руб.';

        return $message;
    }

    public static function prepareMessageToBayer(self $self): string
    {
        $orders = self::getOrdersStringList($self);
        $shopId = $self->getShop()->id;
        $marketName = config('telegram_bot.bot.marketName');
        $botName = config('telegram_bot.bot.botName');

        $link = 'https://t.me/' . $botName . '/' . $marketName . '/?startapp=' . $shopId;
        $tagA = '<a href="' . $link . '">' . $self->getShop()->name . '</a>';

        //TODO  <кол-во товара>
        $message = '<b> Вы оформили заказ № ' . $self->id . '</b>' . "\n"
        . 'Магазин: ' . $tagA  . "\n"
        . 'Содержимое заказа:' . "\n"
        .  $orders
        .  "\n"
        . 'На сумму: ' . $self->getPrice() . ' руб.'
        .  "\n"
        . 'Продавец скоро свяжется с Вами.';

        return $message;
    }

    /**
     * @param ShopOrder $self
     * @return string
     */
    public static function getOrdersStringList(ShopOrder $self): string
    {
        $orders = '';
        foreach ($self->products as $product) {
            $orders .= $product->title .  ' - ' . $product->pivot->quantity . ' шт.' ."\n";
        }

        return $orders;
    }

    public static function getHistory(int $shopId, int $tgUserId)
    {
        /** @see productsWithTrashed */
        return self::with('productsWithTrashed')->where([
                    'shop_id'          => $shopId,
                    'telegram_user_id' => $tgUserId,
              ])->orderBy('shop_orders.created_at', 'DESC')->get();
    }

    public static function getNotificationToBayer(int $orderId, int $tgUserId): string
    {
        $order = self::getOrderByTgUserId($orderId, $tgUserId);

        if(!$order){
            log::error('not find order id: ' . $orderId . 'tg user:' . $tgUserId);
            return  'Error';
        }

        return  self::prepareMessageToBayer($order);
    }

    public static function getOrderByTgUserId(int $orderId, int $tgUserId) :?self
    {
        return self::where(['id' => $orderId, 'telegram_user_id' => $tgUserId])->first();
    }
}
