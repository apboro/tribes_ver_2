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
         'owner' => $this->owner()->first()->name,
         'telegram' => $this->connection()->first()->chat_type,
         'created_at' => $this -> created_at,
         'followers' => $this->countFollowers,
         'balance' => $this->balance / 100
        ];
    }
}
