<?php

namespace App\Http\ApiResources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ExportModerationResource extends JsonResource
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
            "action_date" => Carbon::createFromTimestamp($this->resource->action_date),
            "name" => $this->resource->name,
            "nick_name" => $this->resource->nick_name,
            "action" => $this->resource->action,
        ];
    }
}
