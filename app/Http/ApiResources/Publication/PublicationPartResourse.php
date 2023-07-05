<?php

namespace App\Http\ApiResources\Publication;

use Illuminate\Http\Resources\Json\JsonResource;

class PublicationPartResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'order' => $this->resource->order,
            'publication_id' => $this->resource->publication_id,
            'type' => $this->resource->type,
            'file_path' => $this->resource->file_path,
            'text' => $this->resource->text,
        ];
    }
}
