<?php

namespace App\Http\ApiResources;

use App\Models\TelegramUser;
use App\Models\TelegramUserList;
use App\Repositories\TelegramUserLists\TelegramUserListsRepositry;
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
        $arr_to_search = [];
        if ($request->boolean('banned')) $arr_to_search[] = TelegramUserListsRepositry::TYPE_BAN_LIST;
        if ($request->boolean('muted')) $arr_to_search[] = TelegramUserListsRepositry::TYPE_MUTE_LIST;
        if ($request->boolean('whitelisted')) $arr_to_search[] = TelegramUserListsRepositry::TYPE_WHITE_LIST;

        $communitiesList = $this->resource
            ->communities($arr_to_search)        
            ->where('owner', Auth::user()->id)
            ->where('is_active', true)
            ->where(function ($query) use ($arr_to_search){
                $query->where('telegram_users_community.exit_date', '=', null)
                    ->when(in_array(4, $arr_to_search), function($query) {
                        $query->orWhere('telegram_users_community.status', 'banned');
                    });
            })
            ->when(!empty(array_filter($arr_to_search)), function ($query) use ($arr_to_search) {
                $query->whereHas('telegramUserList', function ($query) use ($arr_to_search) {
                    $query->whereIn('type', $arr_to_search);
                });
            })
            ->get();

        $communitiesList->transform(function ($community) use ($arr_to_search) {
            return [
                'id' => $community->id,
                'title' => $community->title,
                'role' => $community->pivot->role,
                'accession_date' => $community->pivot->accession_date,
                'chat_tags' => $community->tags,
                'status' => $community->findTelegramUserList($this->resource->telegram_id)->typeName ?? null,
            ];
        });

        return [
            'telegram_id' => $this->resource->telegram_id,
            'name' => $this->resource->first_name,
            'last_name' => $this->resource->last_name,
            'user_name' => $this->resource->user_name ? '@' . $this->resource->user_name : null,
            'communities' => $communitiesList,
        ];
    }
}
