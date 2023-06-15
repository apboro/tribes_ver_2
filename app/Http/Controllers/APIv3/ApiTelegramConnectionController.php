<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\Community\ApiTelegramConnectionCreateRequest;
use App\Http\ApiRequests\Community\ApiTelegramConnectionSearchRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\Abs\Messenger;
use App\Services\Telegram;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ApiTelegramConnectionController extends Controller
{
    /**
     * Create telegram connection
     *
     * TODO swagger annotation
     *
     * @param ApiTelegramConnectionCreateRequest $request
     *
     * @return ApiResponse
     * @throws BindingResolutionException
     */
    public function create(ApiTelegramConnectionCreateRequest $request): ApiResponse
    {
        log::info('Create User Bot');

        $service = app()->make(Messenger::$platform[$request->input('platform')]);

        /** @var Telegram $service */
        $result = $service->invokeCommunityConnect(Auth::user(), $request->input('type'), $request->input('telegram_id'));

        if ($result['original']['status'] === 'error') {
            Log::error('telegram_account_not_connected');
            return ApiResponse::error('validation.telegram_account_not_connected');
        }

        return ApiResponse::common($result);
    }

    /**
     * Check telegram connection status
     *
     * TODO swagger annotations
     *
     * @param ApiTelegramConnectionSearchRequest $request
     *
     * @return ApiResponse
     */
    public function checkStatus(ApiTelegramConnectionSearchRequest $request): ApiResponse
    {
        /** @var Telegram $service */
        $service = app()->make(Messenger::$platform[$request['platform']]);

        Log::debug($request['telegram_user_id']);

        try {
            $result = $service->checkCommunityConnect($request['telegram_user_id']);
        } catch (\Exception $e) {
            Log::error($e->getMessage().$e->getFile().$e->getLine());
        }

        return ApiResponse::common($result);

    }
}
