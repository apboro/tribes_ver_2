<?php

namespace App\Models;

use App\Models\Market\ShopOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopOrderProduct extends Model
{
    protected $table = 'shop_order_product_list';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'options'
    ];

    protected $casts = [
        'options' => 'array'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(ShopOrder::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
