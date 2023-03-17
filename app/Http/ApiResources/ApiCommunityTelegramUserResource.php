<?php

namespace App\Http\ApiResources;

use App\Models\TelegramUser;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiCommunityTelegramUserResource extends JsonResource
{
    /** @var TelegramUser */

    public $resource;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'telegram_id' => $this->resource->telegram_id,
            'name'=>$this->resource->first_name,
            'last_name'=>$this->resource->last_name,
            'user_name'=>$this->resource->user_name,
            'accession_date'=>$this->resource->accession_date,
            'communities'=>CommunityResource::collection($this->resource->communities)
        ];
    }
}
