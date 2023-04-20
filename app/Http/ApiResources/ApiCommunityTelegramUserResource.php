<?php

namespace App\Http\ApiResources;

use App\Models\TelegramUser;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ApiCommunityTelegramUserResource extends JsonResource
{
    /** @var TelegramUser */

    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'telegram_id' => $this->resource->telegram_id,
            'name' => $this->resource->first_name,
            'last_name' => $this->resource->last_name,
            'user_name' => $this->resource->user_name ? stristr($this->resource->user_name, env('TELEGRAM_BOT_NAME'))
                ? $this->resource->user_name : '@'.$this->resource->user_name : null,
            'accession_date' => $this->resource->auth_date,
            'communities' => $this->whenLoaded(
                'communities', function () {
                return $this->resource->communities()->where('owner', Auth::user()->id)
                    ->where('is_active', true)
                    ->pluck('title');
            }
            ),
            'user_list' => $this->whenLoaded(
                'userList',
                function () {
                    return $this->resource->userList()->pluck('title', 'type');
                }
            )
        ];
    }
}
