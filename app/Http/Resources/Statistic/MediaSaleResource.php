<?php

namespace App\Http\Resources\Statistic;

use App\Models\Statistic\MProductSale;
use Illuminate\Http\Resources\Json\JsonResource;

/** @property MProductSale $resource */
class MediaSaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "uuid" => $this->resource->uuid,
            "title" => $this->resource->title,
            "tele_login" => $this->resource->tele_login,
            "buy_date" => $this->resource->buy_date,
            "price" => $this->resource->price,
            "status" => $this->resource->status,
        ];
    }
}
