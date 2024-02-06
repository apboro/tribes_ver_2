<?php

namespace App\Http\ApiResources\Market;
use App\Models\Market\ShopOrder;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopOrderResource extends JsonResource
{
    public function toArray($request)
    {
        $productList = [];

        foreach($this->resource->products as $product) {
            $productList[] = [
                'id'       => $product->id,
                'quantity' => $product->pivot->quantity,
                'price'    => $product->pivot->price,
                'title'    => $product->title,
                'image'    => $product->images[0]['file'],
            ];
        }

        return [
            'order_id'         => $this->resource->id,
            'product_list'     => $productList,
            'status_name'      => ShopOrder::STATUS_NAME_LIST[$this->resource->status],
            'type_title'       => ShopOrder::STATUS_TYPE_TITLE[$this->resource->status],
            'description'      => ShopOrder::STATUS_TYPE_DESCRIPTION[$this->resource->status],
            'payments_id'      => $this->resource->payments_id,
            'telegram_user_id' => $this->resource->telegram_user_id,
            'delivery_id'      => $this->resource->delivery_id,
            'delivery'         => $this->resource->delivery,
            'shop_id'          => $this->resource->shop_id,
            'created_at'       => $this->resource->created_at,
        ];
    }
}