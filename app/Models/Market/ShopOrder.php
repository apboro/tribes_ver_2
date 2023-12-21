<?php

namespace App\Models\Market;

use App\Models\Author;
use App\Models\Payment;
use App\Models\Product;
use App\Models\TelegramUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string $token
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
        return $this->product->first()->belongsTo(Author::class);
    }

    public function product(): BelongsToMany
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

        $order->product()->attach($product);

        return $order;
    }

    public function getPrice(): int
    {
        $price = 0;
        foreach($this->product()->get() as $product) {
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
}
