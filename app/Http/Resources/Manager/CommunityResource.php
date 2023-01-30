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
         'owner_name' => $this->whenLoaded('communityOwner')->name,
         'owner_id' => $this->whenLoaded('communityOwner')->id,
         'telegram' => $this->whenLoaded('connection')->chat_type,
         'created_at' => $this -> created_at,
         'followers' => $this->followers_count,
         'balance' => $this->balance,
         'chat_invite_link' => $this->whenLoaded('connection')->chat_invite_link,
        ];
    }
}
