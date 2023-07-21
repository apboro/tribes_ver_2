<?php

namespace App\Http\ApiResources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExportMessageResource extends JsonResource
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
            "telegram_user_id" => $this->resource->telegram_id,
            "group_chat_id" => $this->resource->group_chat_id,
            "name" => $this->resource->name,
            "nick_name" => $this->resource->nick_name,
            "count_messages" => $this->resource->count_messages,
        ];
    }
}
