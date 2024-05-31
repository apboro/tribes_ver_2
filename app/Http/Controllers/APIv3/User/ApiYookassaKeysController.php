<?php

namespace App\Http\Controllers\APIv3\User;

use App\Http\ApiResponses\ApiResponse;
use App\Http\ApiRequests\User\YookassaKeyRequest;
use App\Http\Controllers\Controller;
use App\Services\Yookassa\OAuth;
use Illuminate\Http\Request;

class ApiYookassaKeysController extends Controller
{    
    public function getOAuthLink(YookassaKeyRequest $request)
    {
        return ApiResponse::common(['link' => OAuth::getOAuthLink($request->shop_id)]);
    }

    public function exchangeKeyToOAuth(Request $request)
    {
        $result = OAuth::exchangeKeyToOAuth($request->code ?? 0, $request->state ?? 0);

        return ApiResponse::success($result);
    }
}