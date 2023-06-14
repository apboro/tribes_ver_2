<?php

namespace App\Http\ApiResources\Rules;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiUserRuleResource extends JsonResource
{
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'uuid' => $this->resource->uuid,
            'rules' => $this->resource->rules,
            'title' => $this->resource->title,
            'type' => $this->resource->type,
            'communities_ids' => $this->resource->communities->pluck('id')
        ];

    }
}
