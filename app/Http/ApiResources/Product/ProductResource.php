<?php

namespace App\Http\ApiResources\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProductResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'images' => $this->resource->images,
            'price' => $this->resource->price,
            'shop_id' => $this->resource->shop_id,
            'buyable' => $this->resource->buyable,
            ];
    }
}
