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
            'telegram_user'=>($this->resource->telegramUser)
                ? $this->resource->telegramUser->user_name
                ? '@'.$this->resource->telegramUser->user_name : null : null,
            'event'=>$this->resource->event,
            'action'=>$this->resource->action,
            'done_date'=>$this->resource->created_at->timestamp,
            'community'=>$this->resource->telegramConnections->community->title,
            'community_tags'=>ApiTagResourse::collection($this->resource->telegramConnections->community->tags)->toArray($request)
        ];
    }
}
