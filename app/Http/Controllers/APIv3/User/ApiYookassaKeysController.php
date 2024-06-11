<?php

namespace App\Http\Controllers\APIv3\User;

use App\Http\ApiResponses\ApiResponse;
use App\Http\ApiRequests\User\YookassaExchangeRequest;
use App\Http\ApiRequests\User\YookassaKeyRequest;
use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Services\Yookassa\OAuth;
use Illuminate\Http\Request;

class ApiYookassaKeysController extends Controller
{    
    public function getOAuthLink(YookassaKeyRequest $request)
    {
        return ApiResponse::common(['link' => OAuth::getOAuthLink($request->shop_id)]);
    }

    public function exchangeKeyToOAuth(YookassaExchangeRequest $request)
    {
        $shopId = $request->state;
        $result = OAuth::exchangeKeyToOAuth($request->code, $shopId);
        if ($result['status'] === 'error') {
            return ApiResponse::error($result['message']);
        }
        Shop::find($shopId)->setBuyable(true);

        return ApiResponse::success($result['message']);
    }
}