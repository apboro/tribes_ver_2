<?php

namespace App\Http\Resources\Manager;

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
         'id' => $this ->id,
         'title' => $this -> title,
         'owner_name' => $this->communityOwner->name ?? null,
         'owner_id' => $this->communityOwner->id ?? null ,
         'telegram' => $this->connection->chat_type ?? null,
         'created_at' => $this -> created_at,
         'followers' => $this->followers_count,
         'balance' => $this->balance,
         'chat_invite_link' => $this->connection->chat_invite_link ?? null,
        ];
    }
}
