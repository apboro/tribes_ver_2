<?php

namespace App\Http\ApiResources;

use App\Models\Feedback;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class FeedBackResource extends JsonResource
{
    /** @var Feedback */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'phone' => $this->resource->phone,
            'text' => $this->resource->text,
            'answer' => $this->resource->answer,
            'status' => $this->resource->status,
            'user_id' => $this->resource->user_id,
            'manager_id' => $this->resource->manager_user_id,
            'created_at' => $this->resource->created_at->timestamp,
        ];
    }
}
