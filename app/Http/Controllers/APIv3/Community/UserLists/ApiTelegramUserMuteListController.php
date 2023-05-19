<?php

namespace App\Http\Controllers\APIv3\Community\UserLists;

use App\Http\ApiRequests\ApiMuteListDeleteRequest;
use App\Http\ApiRequests\ApiMuteListFilterRequest;
use App\Http\ApiRequests\ApiMuteListStoreRequest;
use App\Http\ApiResources\ApiListCollection;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\TelegramUserList;
use App\Repositories\TelegramUserLists\TelegramUserListsRepositry;


class ApiTelegramUserMuteListController extends Controller
{
    private TelegramUserListsRepositry $telegramUserListsRepositry;

    public function __construct(TelegramUserListsRepositry $telegramUserListsRepositry)
    {
        $this->telegramUserListsRepositry = $telegramUserListsRepositry;
    }

    /**
     * @param ApiMuteListStoreRequest $request
     * @return ApiResponse
     */
    public function store(ApiMuteListStoreRequest $request):ApiResponse
    {
        $this->telegramUserListsRepositry->add($request,TelegramUserListsRepositry::TYPE_MUTE_LIST);
        return ApiResponse::success();
    }

    /**
     * @param ApiMuteListDeleteRequest $request
     * @return ApiResponse
     */

    public function detach(ApiMuteListDeleteRequest $request):ApiResponse
    {
        $this->telegramUserListsRepositry->detach($request);
        return ApiResponse::success();
    }

    /**
     * @param ApiMuteListFilterRequest $request
     * @return ApiResponse
     */
    public function filter(ApiMuteListFilterRequest $request):ApiResponse
    {
        /** @var TelegramUserList $telegram_list */
        $telegram_list = $this->telegramUserListsRepositry->filter($request,TelegramUserListsRepositry::TYPE_MUTE_LIST);
        return ApiResponse::list()->items(ApiListCollection::make($telegram_list)->toArray($request));
    }
}
