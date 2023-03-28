<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\ApiBlackListDeleteRequest;
use App\Http\ApiRequests\ApiBlackListFilterRequest;
use App\Http\ApiRequests\ApiBlackListStoreRequest;
use App\Http\ApiRequests\ApiWhiteListDeleteRequest;
use App\Http\ApiRequests\ApiWhiteListFilterRequest;
use App\Http\ApiRequests\ApiWhiteListStoreRequest;
use App\Http\ApiResources\ApiListCollection;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\TelegramUserList;
use App\Repositories\TelegramUserLists\TelegramUserListsRepositry;

class ApiTelegramUserWhiteListController extends Controller
{
    private TelegramUserListsRepositry $telegramUserListsRepositry;

    public function __construct(TelegramUserListsRepositry $telegramUserListsRepositry)
    {
        $this->telegramUserListsRepositry = $telegramUserListsRepositry;
    }

    /**
     * @param ApiBlackListStoreRequest $request
     * @return ApiResponse
     */
    public function store(ApiWhiteListStoreRequest $request):ApiResponse
    {
        $this->telegramUserListsRepositry->add($request,TelegramUserListsRepositry::TYPE_WHITE_LIST);
        return ApiResponse::success();
    }

    /**
     * @param ApiWhiteListDeleteRequest $request
     * @return ApiResponse
     */

    public function detach(ApiWhiteListDeleteRequest $request):ApiResponse
    {
        $this->telegramUserListsRepositry->detach($request);
        return ApiResponse::success();
    }

    /**
     * @param ApiWhiteListFilterRequest $request
     * @return ApiResponse
     */
    public function filter(ApiWhiteListFilterRequest $request):ApiResponse
    {
        /** @var TelegramUserList $telegram_list */
        $telegram_list = $this->telegramUserListsRepositry->filter($request,TelegramUserListsRepositry::TYPE_WHITE_LIST);
        return ApiResponse::list()->items(ApiListCollection::make($telegram_list)->toArray($request));
    }
}
