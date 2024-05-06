<?php

namespace App\Http\ApiResources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionUserResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "id" => $this->resource->id,
            "name" => $this->resource->name,
            "slug" => $this->resource->slug,
            "is_active" => $this->resource->is_active,
            "price" => $this->resource->price,
            "period_days" => $this->resource->period_days,
            "sort_order" => $this->resource->sort_order,
            "commission" => $this->resource->commission,
            "price_old" => $this->resource->price_old,
            "badge" => $this->resource->badge,
        ];
    }
}