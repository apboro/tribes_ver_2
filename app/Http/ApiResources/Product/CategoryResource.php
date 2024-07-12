<?php

namespace App\Http\ApiResources\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CategoryResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'shop_id' => $this->resource->shop_id,
            'parent_id' => $this->resource->parent_id,
            'product_count' => $this->resource->product_count ?? 0,
            ];
    }
}
