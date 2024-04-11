<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitedProduct extends Model
{
    public $timestamps = false;
    public $fillable = ['telegram_id', 'shop_id', 'products'];
    protected $casts = ['products' => 'json'];

    protected const MAX_PRODUCTS = 7;

    public function addProduct(int $productId): bool
    {
        $products = $this->products ?? [];
        array_unshift($products, $productId);
        $products = array_slice(array_unique($products), 0, self::MAX_PRODUCTS);
        $this->products = $products;
        
        return $this->save();
    }

    public static function getVisitedProducts(int $shopId, int $telegramId): ?self
    {
        return self::where('shop_id', $shopId)
                        ->where('telegram_id', $telegramId)
                        ->first();
    }
}