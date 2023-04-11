<?php

namespace App\Http\ApiResources;

use App\Http\Resources\CommunitiesResource;
use App\Models\Project;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class ProjectResource extends JsonResource
{
    /** @var Project */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request):array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'user_id' => $this->resource->user_id,
            'created_at' => $this->resource->created_at->timestamp,
        ];
    }
}
