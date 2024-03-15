<?php

namespace App\Http\Controllers\APIv3\User;

use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\ApiRequests\User\UnitpayKeyRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\ApiResources\UnitpayKeyResource;

class ApiUnitpayKeysController extends Controller
{
    public function show(UnitpayKeyRequest $request): ApiResponse
    {
        $unitpayKey = Auth::user()->getUnitpayKeyByShopId($request->shop_id);

        return ApiResponse::common(UnitpayKeyResource::make($unitpayKey)->toArray($request));
    }

    public function save(UnitpayKeyRequest $request): ApiResponse
    {
        Auth::user()->getUnitpayKeyByShopId($request->shop_id)->updateOrCreate(['shop_id' => $request->shop_id], $request->validated());
 
        return ApiResponse::success('common.success');
    }

    public function destroy(UnitpayKeyRequest $request): ApiResponse
    {
        Auth::user()->getUnitpayKeyByShopId($request->shop_id)->delete();

        return ApiResponse::success('common.success');
    }
}