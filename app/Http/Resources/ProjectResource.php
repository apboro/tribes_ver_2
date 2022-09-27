<?php

namespace App\Http\Resources;

use App\Models\Project;

/** @property Project $resource */
class ProjectResource extends \Illuminate\Http\Resources\Json\JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'user_id' => $this->resource->user_id,
            'created_at' => $this->resource->created_at,
        ];
    }
}