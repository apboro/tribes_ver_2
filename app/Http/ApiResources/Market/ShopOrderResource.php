<?php

namespace App\Http\ApiResources\Market;
use App\Models\Market\ShopOrder;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopOrderResource extends JsonResource
{
    public function toArray($request)
    {
        $productList = [];
        $total = 0;
        foreach($this->orderProducts as $product) {
            $trashed = $product->product->deleted_at !== null;
            $productList[] = [
                'id'       => $product->product->id,
                'quantity' => $product->quantity,
                'price'    => $product->price,
                'title'    => $product->product->title,
                'image'    => $product->product->images[0]['file'],
                'trashed'  => $trashed,
                'options'  => $product->options,
            ];

            $total += $product->quantity * $product->price;
        }

        return [
            'order_id'         => $this->resource->id,
            'product_list'     => $productList,
            'status_name'      => $this->resource->getPayStatusName(),
            'type_title'       => ShopOrder::STATUS_TYPE_TITLE[$this->resource->status],
            'description'      => ShopOrder::STATUS_TYPE_DESCRIPTION[$this->resource->status],
            'payments_id'      => $this->resource->payments_id,
            'telegram_user_id' => $this->resource->telegram_user_id,
            'delivery_id'      => $this->resource->delivery_id,
            'delivery'         => $this->resource->delivery,
            'shop_id'          => $this->resource->shop_id,
            'total'            => $total,
            'created_at'       => $this->resource->created_at,
        ];
    }
}