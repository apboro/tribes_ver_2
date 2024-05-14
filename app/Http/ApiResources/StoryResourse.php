<?php

namespace App\Http\ApiResources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class StoryResourse extends JsonResource
{
    public $resource;

    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'content' => $this->resource->content,
            'button' => $this->resource->button,
            'link' => $this->resource->link,
            'sort' => $this->resource->sort,
            'friends' => $this->resource->friends,
            'gradient' => $this->resource->gradient,
            'image' => $this->resource->image ? Storage::disk('public')->url($this->resource->image) : null,
            'ico' => $this->resource->ico ? Storage::disk('public')->url($this->resource->ico) : null,
        ];
    }
}