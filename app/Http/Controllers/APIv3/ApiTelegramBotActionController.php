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

        $list = TelegramBotActionLog::with([
            'telegramConnections.community',
            'telegramUser',
            'telegramConnections.community.tags'
        ])->
        whereHas('telegramConnections.community', function ($query) {
            $query->where('owner', Auth::user()->id);
        });

        if (!empty($request->input('event'))) {
            $list->where('event', 'ilike', '%' . $request->input('event') . '%');
        }

        if (!empty($request->input('action_date_from'))) {
            $list->whereDate('created_at', '>=', $request->input('action_date_from'));
        }

        if (!empty($request->input('action_date_to'))) {
            $list->whereDate('created_at', '<=', $request->input('action_date_to'));
        }

        if (!empty($request->input('community_title'))) {
            $list->whereHas('telegramConnections.community', function ($query) use ($request) {
                $query->where('title', 'ilike', '%'. $request->input('community_title') . '%');
            });
        }
        if ($request->input('tag_names') !== null) {
            if (!empty(array_filter($request->input('tag_names')))) {
                $tagsNames = explode(",", $request->input('tag_names')[0]);
//                return ApiResponse::common($tagsNames);//($request->input('tag_names'));
                $list->whereHas('telegramConnections.community.tags', function ($query) use ($tagsNames) {
//                return ApiResponse::common('privet');//($request->input('tag_names'));
                     return $query->whereIn('tags.name', $tagsNames);
                });
            }
        }

        if (!empty($request->input('user_name'))) {
            $list->whereHas('telegramUser', function ($query) use ($request) {
                $query->where('first_name', 'ilike', '%' . $request->input('user_name') . '%')
                    ->orWhere('last_name', 'ilike', '%' . $request->input('user_name') . '%')
                    ->orWhere('user_name', 'ilike', '%' . $request->input('user_name') . '%');
            });
        }

        $count = $list->count();
        $result = $list->skip($request->offset-1)->take($request->limit)->orderBy('id')->get();

        return ApiResponse::listPagination(['Items-Count'=>$count])->items(new ApiTelegramBotActionLogCollection($result));
    }
}
