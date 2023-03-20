<?php

namespace App\Http\ApiResources;

use App\Models\Models\Tag;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;
class ApiTagResourse extends JsonResource
{

    /**
     * @var Tag $resource
     */
    public $resource;


    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->resource->id,
            'name'=>$this->resource->name
        ];
    }
}
