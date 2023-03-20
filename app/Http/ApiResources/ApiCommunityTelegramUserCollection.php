<?php

namespace App\Http\ApiResources;

use App\Models\TelegramUser;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ApiCommunityTelegramUserCollection extends ResourceCollection
{

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return ApiCommunityTelegramUserResource::collection($this->collection);
    }
}
