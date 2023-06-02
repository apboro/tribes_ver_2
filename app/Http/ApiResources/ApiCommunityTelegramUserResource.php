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
            'user_name' => $this->resource->user_name ? '@' . $this->resource->user_name : null,
            'communities' => $this->whenLoaded(
                'communities', function () {
                $communities = $this->resource->communities()->where('owner', Auth::user()->id)
                    ->where('is_active', true)
                    ->where(function ($query){
                        $query->where('telegram_users_community.exit_date', '=', null)
                            ->orWhere('telegram_users_community.status', 'banned');
                    })
                    ->get();
                $communitiesList = [];
                foreach ($communities as $community) {
                    foreach ($this->resource->userList as $list) {
                        if ($list->community_id = $community->id) {
                            $listTypeForCurrentCommunity = $list->type;
                        }
                    }
                    $communitiesList[] = [
                        'id' => $community->id,
                        'title' => $community->title,
                        'role' => $community->pivot->role,
                        'accession_date' => $community->pivot->accession_date,
                        'chat_tags' => $community->tags,
                        'is_in_list_type' => $listTypeForCurrentCommunity ?? null,
                    ];

                }
                return $communitiesList;
            }),
        ];
    }
}
