<?php

namespace App\Http\ApiResources;

use App\Models\TelegramBotActionLog;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiTelegramBotActionLogResource extends JsonResource
{
    /** @var TelegramBotActionLog */
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
            'telegram_user'=>$this->resource->telegramUser->user_name,
            'action_type'=>$this->resource->actionType->name,
            'bot_action'=>$this->resource->action_done,
            'done_date'=>$this->resource->created_at->toDateTimeString(),
            'community'=>$this->resource->community->title,
            'community_tags'=>ApiTagResourse::collection($this->resource->community->tags)->toArray($request)

        ];
    }
}
