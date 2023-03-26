<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\ApiBlackListDeleteRequest;
use App\Http\ApiRequests\ApiBlackListFilterRequest;
use App\Http\ApiRequests\ApiBlackListStoreRequest;
use App\Http\ApiResources\ApiListCollection;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;

use App\Models\TelegramUserList;
use App\Repositories\TelegramUserLists\TelegramUserListsRepositry;

class ApiTelegramUserBlackListController extends Controller
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
    public function store(ApiBlackListStoreRequest $request):ApiResponse
    {
       $this->telegramUserListsRepositry->add($request,TelegramUserListsRepositry::TYPE_BLACK_LIST);
       return ApiResponse::success();
    }

    /**
     * @param ApiBlackListDeleteRequest $request
     * @return ApiResponse
     */

    public function detach(ApiBlackListDeleteRequest $request):ApiResponse
    {
        $this->telegramUserListsRepositry->detach($request);
        return ApiResponse::success();
    }

    /**
     * @param ApiBlackListFilterRequest $request
     * @return ApiResponse
     */
    public function filter(ApiBlackListFilterRequest $request):ApiResponse
    {
        /** @var TelegramUserList $telegram_list */
        $telegram_list = $this->telegramUserListsRepositry->filter($request);
        return ApiResponse::list()->items(ApiListCollection::make($telegram_list)->toArray($request));
    }
}
