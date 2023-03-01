<?php

namespace App\Http\Controllers\APIv3\User;

use App\Http\ApiRequests\ApiListMessengersRequest;
use App\Http\ApiResources\TelegramAccountResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiMessengersController extends Controller
{
    public function list(ApiListMessengersRequest $request)
    {
        $telegram_accounts = Auth::user()->telegramData();
        return ApiResponse::common([
            'data'=> TelegramAccountResource::collection($telegram_accounts),
        ]);
    }
}
