<?php

namespace App\Http\ApiResources\Rules;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ApiUserRulesCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return ApiUserRuleResource::collection($this->collection)->toArray($request);
    }
}
