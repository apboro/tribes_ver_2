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
            'communities'=>$this->whenLoaded(
                'communities', function () {
                $exactMatches = $this->resource->communities()->where('owner',  Auth::user()->id)->pluck('title');
                return $exactMatches;
            }
            ),
            'user_list'=>$this->whenLoaded(
                'userList',
                $this->resource->userList()->pluck('type')
            )
        ];
    }
}
