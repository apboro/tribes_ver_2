<?php

namespace App\Http\ApiResources\Market;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopOrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'order_id'   => $this->resource->id,
            'product_list' => $this->resource->product,
            'status' => $this->resource->status,
            'payments_id' => $this->resource->payments_id,
            'telegram_user_id' => $this->resource->telegram_user_id,
            'delivery_id' => $this->resource->delivery_id,
        ];
    }
}