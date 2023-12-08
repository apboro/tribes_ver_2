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
            'image' => $this->resource->image,
            'price' => $this->resource->price,
            'author_id' => $this->resource->author_id
            ];
    }
}
