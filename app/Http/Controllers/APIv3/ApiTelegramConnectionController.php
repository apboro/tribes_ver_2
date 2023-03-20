<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\ApiTelegramConnectionCreateRequest;
use App\Http\ApiRequests\ApiTelegramConnectionSearchRequest;
use App\Http\ApiResources\TelegramResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\TelegramConnection;
use App\Services\Abs\Messenger;
use App\Services\Telegram;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $service = app()->make(Messenger::$platform[$request->input('platform')]);

        $result = $service->invokeCommunityConnect(Auth::user(), $request->input('type'), $request->input('telegram_id'));

        if ($result['original']['status'] === 'error') {
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
        /* @var  $service Telegram */

        $service = app()->make(Messenger::$platform[$request['platform']]);

        $hash = $request['hash'];

        $result = $service->checkCommunityConnect($hash);

        return ApiResponse::common($result);

    }
}
