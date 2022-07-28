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
            "title" => $this->resource->mProduct->entityObj->title??'Не определилось',
            "tele_login" => $this->resource->teleUser->user_name??'',
            "buy_date" => $this->resource->created_at,
            "price" => $this->resource->price,
            "status" => $this->resource->payment->status??'Не определилось',
        ];
    }
}
