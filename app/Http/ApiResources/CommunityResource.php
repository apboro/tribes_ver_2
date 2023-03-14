<?php

namespace App\Http\ApiResources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommunityResource extends JsonResource
{
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
            "created_at" => $this->resource->created_at,
            "updated_at" => $this->resource->updated_at,
            "hash" => $this->resource->hash,
            "balance" => $this->resource->balance,
            "donate" => $this->resource->donate,
            "type" => $this->resource->connection->chat_type,
            "tags"=>$this->resource->tags,
        ];
    }
}
