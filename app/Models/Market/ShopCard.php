<?php

namespace App\Models\Market;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ShopCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'telegram_user_id',
        'shop_id',
        'product_id',
        'quantity',
    ];

    public $table = 'shop_cards';

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public static function cardUpdateOrCreate(array $card): void
    {
        $shopCardCriteria = self::prepareCriteria($card);

        parent::updateOrCreate($shopCardCriteria, $card);
    }

    private static function prepareCriteria(array $card): array
    {
        return [
            'telegram_user_id' => $card['telegram_user_id'],
            'shop_id'          => $card['shop_id'],
            'product_id'       => $card['product_id'],
        ];
    }

    private static function getUserShopCard(int $shopId, int $telegramId, array $products): Builder
    {
        return self::with('product')
                        ->where('shop_id', $shopId)
                        ->where('telegram_user_id', $telegramId)
                        ->whereIn('product_id', $products);
    }

    private static function prepareData(Builder $builder): array
    {
        $data = [];

        foreach($builder->get() as $card) {
            $data[$card->product_id]  = [
                'quantity' => $card->quantity,
                'price'    => $card->product->price,
            ];
        }

        return $data;
    }

    public static function transferProductsToOrder(ShopOrder $shopOrder, $shopId, $telegramId, $products): ShopOrder
    {
        $userShopCardListSql = self::getUserShopCard($shopId, $telegramId, $products);

        $data = self::prepareData($userShopCardListSql);
        $shopOrder->products()->attach($data);
        $userShopCardListSql->delete();

        return $shopOrder;
    }
}
