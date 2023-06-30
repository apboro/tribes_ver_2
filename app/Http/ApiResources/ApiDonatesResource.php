<?php

namespace App\Http\ApiResources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiDonatesResource extends JsonResource
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
            'id' => $this->resource->id,
            'image' => $this->resource->image,
            'donate_is_active' => $this->resource->donate_is_active,
            'description' => $this->resource->description,
            'variants'=>ApiDonatesVariantsResource::collection($this->resource->variants)
        ];
    }
}
