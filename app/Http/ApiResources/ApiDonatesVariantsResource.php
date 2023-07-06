<?php

namespace App\Http\ApiResources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiDonatesVariantsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            "id" => $this->resource->id,
            "donate_id" => $this->resource->donate_id,
            $this->resource->variant_name . '_is_active' => $this->resource->isActive,
            $this->resource->variant_name . '_button' => $this->resource->description,
            "created_at" => $this->resource->created_at,
            "variant_name" => $this->resource->variant_name,
            'min_price' => $this->resource->min_price,
            "max_price" => $this->resource->max_price,
            $this->resource->variant_name => $this->resource->price,
        ];

    }
}
