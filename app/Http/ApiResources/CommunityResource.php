<?php

namespace App\Http\ApiResources;

use App\Models\Community;
use Illuminate\Http\Resources\Json\JsonResource;

class CommunityResource extends JsonResource
{

    /**  @var Community */
    public $resource;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->resource->id,
            "title" => $this->resource->title,
            "image" => $this->resource->image,
            "description" => $this->resource->description,
            "created_at" => $this->resource->created_at->timestamp,
            "updated_at" => $this->resource->updated_at->timestamp,
            "balance" => $this->resource->balance,
            "type" => $this->resource->connection->chat_type,
            "tags"=>$this->resource->tags->makeHidden('pivot'),
            "rules"=>$this->resource->communityRule ?? [],
        ];
    }
}
