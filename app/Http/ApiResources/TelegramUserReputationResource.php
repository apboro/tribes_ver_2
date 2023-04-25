<?php

namespace App\Http\ApiResources;

use Illuminate\Http\Resources\Json\JsonResource;

class TelegramUserReputationResource extends JsonResource
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
            "community_id" => $this->resource->community_id,
            "telegram_user_id" => $this->resource->telegram_user_id,
            "messages_count" => $this->resource->messages_count,
            "reputation_count" => $this->resource->reputation_count
        ];

    }
}
