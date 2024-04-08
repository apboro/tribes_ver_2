<?php

namespace App\Http\Controllers\APIv3\User;

use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\ApiRequests\User\UnitpayKeyRequest;
use App\Http\ApiResources\UnitpayKeyResource;
use App\Models\UnitpayKey;
use App\Services\Unitpay\Payment as UnitpayPayment;
use Illuminate\Support\Facades\Auth;

class ApiUnitpayKeysController extends Controller
{
    public function show(UnitpayKeyRequest $request): ApiResponse
    {
        $unitpayKey = Auth::user()->getUnitpayKeyByShopId($request->shop_id);

        return ApiResponse::common(UnitpayKeyResource::make($unitpayKey)->toArray($request));
    }

    public function save(UnitpayKeyRequest $request): ApiResponse
    {
        if (UnitpayKey::isKeysUsed($request->project_id, $request->secretKey, $request->shop_id)){
            return ApiResponse::error('validation.unitpay.keys_used');
        }

        $resultOfTest = app(UnitpayPayment::class)->testKeys($request->project_id, $request->secretKey);
        if ($resultOfTest['success'] === false) {
            return ApiResponse::error($resultOfTest['message']);
        }
         
        Auth::user()->getUnitpayKeyByShopId($request->shop_id)->updateOrCreate(['shop_id' => $request->shop_id], $request->validated());
 
        return ApiResponse::success('common.success');
    }

    public function destroy(UnitpayKeyRequest $request): ApiResponse
    {
        Auth::user()->getUnitpayKeyByShopId($request->shop_id)->delete();

        return ApiResponse::success('common.success');
    }
}