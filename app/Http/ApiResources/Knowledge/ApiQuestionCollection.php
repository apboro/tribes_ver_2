<?php


namespace App\Http\ApiResources\Knowledge;


use Illuminate\Http\Resources\Json\ResourceCollection;

class ApiQuestionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return ApiQuestionResource::collection($this->collection);
    }
}