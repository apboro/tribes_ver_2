<?php

namespace App\Http\Resources\Statistic;

use App\Models\Statistic\MProduct;
use Illuminate\Http\Resources\Json\JsonResource;

/** @property object $resource */
class MediaProductResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "uuid" => $this->resource->uuid,
            "title" => $this->resource->title,
            "actual_price" => $this->resource->price,
            "count_sales" => $this->resource->c_sales,
            "total_cost" => $this->resource->total_cost,
            "public_date" => $this->resource->create_date
        ];
    }
}
