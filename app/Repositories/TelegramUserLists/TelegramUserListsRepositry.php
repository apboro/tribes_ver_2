<?php

namespace App\Repositories\TelegramUserLists;

use App\Http\ApiRequests\ApiRequest;
use App\Models\TelegramUserList;
use Illuminate\Support\Facades\Auth;

class TelegramUserListsRepositry
{
    const TYPE_BLACK_LIST = 1;
    const TYPE_WHITE_LIST = 2;
    const TYPE_BAN_LIST = 4;
    const TYPE_MUTE_LIST = 3;
    const SPAMMER = 1;
    public function add(ApiRequest $request, int $type=self::TYPE_BLACK_LIST):void
    {
        /** @var TelegramUserList $telegram_list */
        $telegram_list = TelegramUserList::where('telegram_id','=',$request->input('telegram_id'))->first();

        if($telegram_list === null){
            $telegram_list = TelegramUserList::create([
                'telegram_id'=>$request->input('telegram_id'),
                'type'=>$type,
            ]);

        }
        $telegram_list->communities()->syncWithoutDetaching($request->input('community_ids'));
        if($request->input('is_spammer')){
            $telegram_list->listParameters()->sync([self::SPAMMER]);
        }
    }
    
    public function detach(ApiRequest $request):void
    {

        /** @var TelegramUserList $telegram_list */
        $telegram_list = TelegramUserList::where('telegram_id','=',$request->input('telegram_id'))->first();

        $telegram_list->communities()->detach($request->input('community_ids'));

        /** @var TelegramUserList $telegram_list */
        $telegram_list = TelegramUserList::withCount('communities')->
                        where('telegram_id','=',$request->input('telegram_id'))->
                        first();

        if($telegram_list->communities_count === 0){
            $telegram_list->delete();
        }
    }

    public function filter(ApiRequest $request,int $type=self::TYPE_BLACK_LIST){
        $query = TelegramUserList::with(['communities','telegramUser','listParameters'])->
        whereHas('communities', function ($query) {
            $query->where('owner', Auth::user()->id);
        });
        $query->where('type','=',$type);
        if(!empty($request->input('is_spammer'))){
            $query->whereHas('listParameters', function ($query) use ($request) {
                $query->where('telegram_user_list_parameters.list_parameter_id', '=',self::SPAMMER);
            });
        }

        if(!empty($request->input('community_id'))){
            $query->whereHas('communities', function ($query) use ($request) {
                $query->where('communities.id', $request->input('community_id'));
            });
        }

        if(!empty($request->input('telegram_name'))){
            $query->whereHas('telegramUser',function($query) use ($request){
                $query->where('first_name','ilike','%'.$request->input('telegram_name').'%')
                    ->orWhere('last_name','ilike','%'.$request->input('telegram_name').'%')
                    ->orWhere('user_name','ilike','%'.$request->input('telegram_name').'%');
            });
        }

        return $query->orderBy('created_at')->paginate(10);
    }
}