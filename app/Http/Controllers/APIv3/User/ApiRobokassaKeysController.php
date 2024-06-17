<?php

namespace App\Http\Controllers\APIv3\User;

use App\Http\ApiRequests\User\RobokassaKeyRequest;
use App\Http\ApiResources\RobokassaKeyResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\RobokassaKey;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;

class ApiRobokassaKeysController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  RobokassaKeyRequest  $request
     * @return ApiResponse
     */
    public function update(RobokassaKeyRequest $request): ApiResponse
    {
        if (RobokassaKey::isKeysUsed(
            $request->merchant_login,
            $request->first_password,
            $request->second_password,
            $request->shop_id
        )
        ) {
            return ApiResponse::error('validation.robokassa.keys_used');
        }

        $robokassaKey = Auth::user()
            ->getRobokassaKeyByShopId($request->shop_id)
            ->updateOrCreate(['shop_id' => $request->shop_id], $request->validated());

        if ($robokassaKey) {
            Shop::find($request->shop_id)->setBuyable(true);
        }

        return ApiResponse::success('common.success');
    }

    /**
     * Display the specified resource.
     *
     * @param  RobokassaKeyRequest  $request
     * @return ApiResponse
     */
    public function show(RobokassaKeyRequest $request): ApiResponse
    {
        $robokassaKeys = Auth::user()->getRobokassaKeyByShopId($request->shop_id);

        return ApiResponse::common(RobokassaKeyResource::make($robokassaKeys));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  RobokassaKeyRequest  $request
     * @return ApiResponse
     */
    public function destroy(RobokassaKeyRequest $request): ApiResponse
    {
        $shop = Auth::user()->shops()->find($request->shop_id);

        if (!$robokassaKey = $shop->robokassaKey) {
            return ApiResponse::notFound('common.not_found');
        }

        $robokassaKey->delete();

        return ApiResponse::success('common.success');
    }
}
