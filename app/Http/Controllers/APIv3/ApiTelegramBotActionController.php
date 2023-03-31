<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\ApiTelegramActionLogFilterRequest;
use App\Http\ApiRequests\ApiTelegramActionLogListRequest;
use App\Http\ApiResources\ApiTelegramBotActionLogCollection;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\TelegramBotActionLog;
use Illuminate\Support\Facades\Auth;

class ApiTelegramBotActionController extends Controller
{
    /**
     * @param ApiTelegramActionLogFilterRequest $request
     * @return ApiResponse
     */
    public function filter(ApiTelegramActionLogFilterRequest $request): ApiResponse
    {
        $query = TelegramBotActionLog::with([
            'telegramConnections.community',
            'telegramUser',
            'telegramConnections.community.tags'
        ])->
        whereHas('telegramConnections.community', function ($query) {
            $query->where('owner', Auth::user()->id);
        });

        if(!empty($request->input('event'))){
            $query->where('event','ilike','%'.$request->input('event').'%');
        }

        if(!empty($request->input('action_date_from'))){
            $query->whereDate('created_at','>=',$request->input('action_date_from'));
        }

        if(!empty($request->input('action_date_to'))){
            $query->whereDate('created_at','<=',$request->input('action_date_to'));
        }

        if(!empty($request->input('community_title'))){
            $query->whereHas('telegramConnections.community',function($query) use ($request){
                $query->whereIn('title',$request->input('community_title'));
            });
        }

        if(!empty($request->input('tags'))){
            $query->whereHas('telegramConnections.community.tags',function($query) use ($request){
                $query->whereIn('tags.name',$request->input('tags_names'));
            });
        }

        if(!empty($request->input('user_name'))){
            $query->whereHas('telegramUser',function($query) use ($request){
                $query->where('first_name','ilike','%'.$request->input('user_name').'%')
                    ->orWhere('last_name','ilike','%'.$request->input('user_name').'%')
                    ->orWhere('user_name','ilike','%'.$request->input('user_name').'%');
            });
        }

        $result = $query->paginate(25);
        return ApiResponse::listPagination()->items(new ApiTelegramBotActionLogCollection($result));
    }
}
