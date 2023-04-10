<?php

namespace App\Http\ApiResources;

use App\Models\TelegramUserList;
use App\Repositories\TelegramUserLists\TelegramUserListsRepositry;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiListResource extends JsonResource
{
    /**
     * @var TelegramUserList $resource
    */
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
            'telegram_id'=>$this->resource->telegram_id,
            'user_name'=>$this->resource->telegramUser->user_name,
            'first_name'=>$this->resource->telegramUser->first_name,
            'last_name'=>$this->resource->telegramUser->last_name,
            'block_date'=>$this->resource->created_at->timestamp,
            'communities'=>$this->whenLoaded(
                'communities',
                $this->resource->communities()->where('type','=',$this->resource->type)->pluck('title')
            ),
            'parameter'=>$this->whenLoaded(
                'listParameters',
                $this->resource->listParameters()->pluck('name')
            )
        ];
    }
}
