<?php

namespace App\Http\Resources\Statistic;

use Illuminate\Http\Resources\Json\JsonResource;

class FinanceResource extends JsonResource
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
            "first_name" => $this->resource->first_name,
            "user_name" => $this->resource->user_name,
            "add_balance" => $this->resource->add_balance,
            "payable_type" => $this->resource->payable_type,
        ];
    }
}
