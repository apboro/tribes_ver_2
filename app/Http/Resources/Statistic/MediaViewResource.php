<?php

namespace App\Http\Resources\Statistic;

use Illuminate\Http\Resources\Json\JsonResource;
/** @property object $resource */
class MediaViewResource extends JsonResource
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
            "uuid" => $this->resource->uuid,
            "user_id" => $this->resource->user_id,
            "user_name" => $this->resource->user_name,
            "title" => $this->resource->title,
            "time_view" => $this->resource->time_view,
        ];
    }
}
{

}