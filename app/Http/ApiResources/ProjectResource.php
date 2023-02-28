<?php

namespace App\Http\ApiResources;

use App\Http\Resources\CommunitiesResource;
use App\Models\Project;
use http\Env\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /** @var Project */
    public $resource;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request):array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'user_id' => $this->resource->user_id,
            'created_at' => $this->resource->created_at,
            'communities' => new CommunitiesResource($this->resource->communities()->get()),
        ];
    }
}
