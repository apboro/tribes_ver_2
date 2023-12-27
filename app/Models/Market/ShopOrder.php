<?php

namespace App\Models\Market;

use App\Events\BuyProductEvent;
use App\Events\FeedBackAnswer;
use App\Models\Author;
use App\Models\Payment;
use App\Models\Product;
use App\Models\TelegramUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Event;

/**
 * @property string $token
 * @property int $telegram_user_id
 */
class ShopOrder extends Model
{
    use HasFactory;

    protected $table = 'shop_orders';

    protected $fillable = [
        'telegram_user_id',
        'delivery_id',
    ];

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function author()
    {
        return $this->products->first()->belongsTo(Author::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'shop_order_product_list', 'order_id');
    }

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(ShopDelivery::class, 'id');
    }

    public static function makeByUser(TelegramUser $user, Product $product, string $address): self
    {
        $shopDelivery = ShopDelivery::makeByUser($user, $address);
        /** @var ShopOrder $order */
        $order = self::make($user, $shopDelivery);

        $order->products()->attach($product);

        return $order;
    }

    public function getPrice(): int
    {
        $price = 0;
        foreach($this->products()->get() as $product) {
            $price += $product->price;
        }

        return $price;
    }

    public static function make(TelegramUser $user, ShopDelivery $shopDelivery): self
    {
        return self::create([
            'telegram_user_id' => $user->telegram_id,
            'delivery_id'      => $shopDelivery->id,
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

        $messageForOwner = self::prepareMessageToOwner($self, $payment);
        $messageForBayer = self::prepareMessageToBayer($self, $payment);

        Event::dispatch(new BuyProductEvent($messageForOwner, $self->products->first()->author->user));
        Event::dispatch(new BuyProductEvent($messageForBayer, $bayerUser->user));
    }

    private static function prepareMessageToOwner(self $self, Payment $payment): string
    {
        $orders = self::getOrdersStringList($self);

        $phone = $self->delivery->phone;
        $phoneString = $phone ? '   - телефон: ' . $phone . "\n": '';
        $userName = $payment->telegramUser->user_name ?? '';

        $a = '<a href="http://t.me/'. $userName . '">'. $userName . '</a>';
        //TODO  <кол-во товара>
       $message = '<b> Оплачен заказ № ' . $self->id . '</b>' . "\n"
        . 'Контакты покупателя:' . "\n"
        . '   - телеграм: ' . $a . "\n"
        .  $phoneString
        . '   - почта: ' . $self->delivery->email . "\n"
        . '   - адрес доставки:'. "\n"
        .  $self->delivery->address . "\n"
        .  "\n"
        . 'Содержимое заказа:' . "\n"
        .  $orders
        .  "\n"
        . 'Общая сумма: ' . $self->getPrice() . ' руб.';

        return $message;
    }

    private static function prepareMessageToBayer(self $self, Payment $payment): string
    {
        $orders = self::getOrdersStringList($self);

        $link = 'https://t.me/' . config('telegram_bot.bot.botName') . '/' . config('telegram_bot.bot.marketName') . '/?startapp=' . $self->author->id;;
        $a = '<a href="' . $link . '">' . $self->author->name . '</a>';

        //TODO  <кол-во товара>
        $message = '<b> Вы оплатили заказ № ' . $self->id . '</b>' . "\n"
        . 'Магазин: ' . $a  . "\n"
        . 'Содержимое заказа:' . "\n"
        .  $orders
        .  "\n"
        . 'Общая сумма: ' . $self->getPrice() . ' руб.'
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
            $orders .= $product->title . "\n";
        }
        return $orders;
    }
}
