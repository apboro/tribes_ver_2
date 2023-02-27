<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\ApiTelegramConnectionCreateRequest;
use App\Http\ApiRequests\ApiTelegramConnectionSearchRequest;
use App\Http\ApiResources\TelegramResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\TelegramConnection;
use App\Services\Abs\Messenger;
use Illuminate\Support\Facades\Auth;

class ApiTelegramConnectionController extends Controller
{

    public function create(ApiTelegramConnectionCreateRequest $request):ApiResponse
    {
        $service = app()->make(Messenger::$platform[$request->input('platform')]);
        $result = $service->invokeCommunityConnect(Auth::user(), $request->input('type'));
        if($result['original']['status'] == 'error'){
            return ApiResponse::error('validation.telegram_account_not_connected');
        }
        return ApiResponse::common(['data'=>$result]);
    }

    public function checkStatus(ApiTelegramConnectionSearchRequest $request):ApiResponse
    {
        $telegram_connection = TelegramConnection::where('hash','=',$request->hash)->first();
        if(empty($telegram_connection)){
            return ApiResponse::notFound('validation.telegram_connection_not_exists');
        }
        return ApiResponse::common(['telegram_connection'=>(new TelegramResource($telegram_connection))]);
    }
}
