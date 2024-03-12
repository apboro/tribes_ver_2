<?php

namespace App\Http\ApiResources\Publication;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PublicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $addInfo = [];
        if (!isset($this->resource->lv)) {
            $addInfo = [
                'last_visit' => $request->user('sanctum') && $this->resource->lastVisit($request->user('sanctum')->id) ?
                    $this->resource->lastVisit($request->user('sanctum')->id)->last_visited : null,
                'is_favourite' => $request->user('sanctum') && $this->resource->isFavourite($request->user('sanctum')->id),
                'parts' => PublicationPartCollection::make($this->resource->parts)->toArray($request)
            ];
        }

        $price = $this->resource->price;
        if ($price && $request->user('sanctum') && $this->resource->isPublicationBuyed($request->user('sanctum')->id)) {
            $price = 0;
        }

        return [
            'id' => $this->resource->id,
            'uuid' => $this->resource->uuid,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'is_active' => $this->resource->is_active,
            'background_image' => $this->resource->background_image,
            'price' => $price,
            'author_id' => $this->resource->author_id,
            'author_name' => $this->resource->author->name ?? '',
            ] + $addInfo;
    }
}
