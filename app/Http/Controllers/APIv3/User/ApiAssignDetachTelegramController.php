<?php

namespace App\Http\Controllers\APIv3\User;

use App\Http\ApiRequests\ApiAssignTelegramRequest;
use App\Http\ApiRequests\ApiDetachTelegramRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\ApiResponses\ApiResponseSuccess;
use App\Repositories\Author\AuthorRepositoryContract;
use App\Http\Controllers\Controller;

class ApiAssignDetachTelegramController extends Controller
{
    private $authorRepo;

    public function __construct(
        AuthorRepositoryContract $authorRepo
    ) {
        $this->authorRepo = $authorRepo;
    }
    public function assignTelegramAccount(ApiAssignTelegramRequest $request)
    {
        if (!$request['id']) {
            return ApiResponse::validationError()->addError('telegram', 'telegram.assign_telegram_no_data');
        }
        if($this->authorRepo->assignOutsideAccount($request->all(), 'Telegram')){
            return ApiResponse::success('telegram.tlg_account_assigned');
        }

        return ApiResponse::error('telegram.process_account_error');
    }

    public function detachTelegramAccount(ApiDetachTelegramRequest $request)
    {
        //TODO logic after TZ
    }
}
