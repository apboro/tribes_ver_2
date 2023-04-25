<?php

namespace App\Http\ApiResources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ApiRankRuleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return ApiRankRuleResource::collection($this->collection);
    }
}
