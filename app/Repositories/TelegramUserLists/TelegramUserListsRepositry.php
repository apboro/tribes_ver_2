<?php

namespace App\Repositories\TelegramUserLists;

use App\Http\ApiRequests\ApiRequest;
use App\Models\Community;
use App\Models\TelegramUserList;
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use Illuminate\Support\Facades\Auth;

class TelegramUserListsRepositry
{
    protected TelegramMainBotService $telegramMainBotService;

    public function __construct(
        TelegramMainBotService $telegramMainBotService
    )
    {

        $this->telegramMainBotService = $telegramMainBotService;
    }

    const TYPE_BLACK_LIST = 1;
    const TYPE_WHITE_LIST = 2;
    const TYPE_MUTE_LIST = 3;
    const TYPE_BAN_LIST = 4;
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
        $telegram_list->communities()->sync($request->input('community_ids'),['type'=>$type]);
        if($request->input('is_spammer')){
            $telegram_list->listParameters()->sync([self::SPAMMER]);
        }
        if($type === self::TYPE_BLACK_LIST){
            foreach($request->input('community_ids') as $community_id){
                try {
                    /** @var Community $community */
                    $community = Community::where('id', $community_id)->first();
                    $community_telegram_chat_id = $community->connection->chat_id;
                    $this->telegramMainBotService->kickUser(
                        config('telegram_bot.bot.botName'),
                        $request->input('telegram_id'),
                        $community_telegram_chat_id
                    );
                } catch (\Exception $e){
                    TelegramLogService::staticSendLogMessage('Black list error'. $e);
                }
            }
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
        whereHas('communities', function ($query) use ($type){
            $query->where('owner', Auth::user()->id)->where('list_community_telegram_user.type','=',$type);
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