<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\ApiBanListDeleteRequest;
use App\Http\ApiRequests\ApiBanListFilterRequest;
use App\Http\ApiRequests\ApiBanListStoreRequest;
use App\Http\ApiResources\ApiListCollection;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\TelegramUserList;
use App\Repositories\TelegramUserLists\TelegramUserListsRepositry;

class ApiTelegramUserBanListController extends Controller
{
    private TelegramUserListsRepositry $telegramUserListsRepositry;

    public function __construct(TelegramUserListsRepositry $telegramUserListsRepositry)
    {
        $this->telegramUserListsRepositry = $telegramUserListsRepositry;
    }

    /**
     * @param ApiBanListStoreRequest $request
     * @return ApiResponse
     */
    public function store(ApiBanListStoreRequest $request):ApiResponse
    {
        $this->telegramUserListsRepositry->add($request,TelegramUserListsRepositry::TYPE_BAN_LIST);
        return ApiResponse::success();
    }

    /**
     * @param ApiBanListDeleteRequest $request
     * @return ApiResponse
     */

    public function detach(ApiBanListDeleteRequest $request):ApiResponse
    {
        $this->telegramUserListsRepositry->detach($request);
        return ApiResponse::success();
    }

    /**
     * @param ApiBanListFilterRequest $request
     * @return ApiResponse
     */
    public function filter(ApiBanListFilterRequest $request):ApiResponse
    {
        /** @var TelegramUserList $telegram_list */
        $telegram_list = $this->telegramUserListsRepositry->filter($request,TelegramUserListsRepositry::TYPE_BAN_LIST);
        return ApiResponse::list()->items(ApiListCollection::make($telegram_list)->toArray($request));
    }
}
