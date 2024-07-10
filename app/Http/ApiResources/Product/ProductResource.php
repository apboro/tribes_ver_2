<?php

namespace App\Http\ApiResources\Product;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProductResource extends JsonResource
{

    public function toArray($request)
    {
        $visited = [];
        if (isset($this->resource->visited)) {
            $visited['visited'] = $this->resource->visited;
        }

        return [
            'id'            => $this->resource->id,
            'title'         => $this->resource->title,
            'description'   => $this->resource->description,
            'images'        => $this->resource->images,
            'price'         => $this->resource->price,
            'shop_id'       => $this->resource->shop_id,
            'buyable'       => $this->resource->buyable,
            'category_id'   => $this->resource->category_id,
            'category_name' => $this->resource->category->name ?? 'Без категории',
            'status'        => $this->resource->status,
            'type'          => $this->resource->type,
        ] + $this->typeLinkResource() + $visited;
    }
}
