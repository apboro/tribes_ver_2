<?php


namespace App\Http\ApiResources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class ApiQuestionCategoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return ApiQuestionCategoryResource::collection($this->collection);
    }
}