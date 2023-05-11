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
            'user_name' => $this->resource->user_name ? '@'.$this->resource->user_name : null,
            'communities' => $this->whenLoaded(
                'communities', function () {
                return $this->resource->communities()->where('owner', Auth::user()->id)
                    ->where('is_active', true)
                    ->wherePivot('exit_date','=', null)->orWherePivot('status', 'banned')
                    ->get()
                    ->map(function ($community) {
                        $accessionDate = $community->pivot->accession_date;
                        return [
                            'id' => $community->id,
                            'title' => $community->title,
                            'accession_date' => $accessionDate,
                            'chat_tags' => $community->tags
                        ];
                    });
            }),
            'user_list' => $this->whenLoaded(
                'userList',
                function () {
                    return $this->resource->userList;
                }
            )
        ];
    }
}
