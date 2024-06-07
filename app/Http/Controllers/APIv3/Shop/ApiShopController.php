<?php

namespace App\Http\Controllers\APIv3\Shop;

use App\Domain\Entity\Shop\CheckShopIsAvailable;
use App\Http\ApiRequests\Shop\ApiShopDelete;
use App\Http\ApiRequests\Shop\ApiShopShowRequest;
use App\Http\ApiRequests\Shop\ApiShopShowListRequest;
use App\Http\ApiRequests\Shop\ApiShopShowMyListRequest;
use App\Http\ApiRequests\Shop\ApiShopGetPaymentSystemsRequest;
use App\Http\ApiRequests\Shop\ApiShopSetPaymentSystemRequest;
use App\Http\ApiRequests\Shop\ApiShopStoreRequest;
use App\Http\ApiRequests\Shop\ApiShopUpdateRequest;
use App\Http\ApiResources\ShopResourse;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\UnitpayKey;
use App\Services\Shop\ShopPayments;
use App\Services\Unitpay\Payment as UnitpayPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class ApiShopController extends Controller
{
    private function prepareShop($request): array
    {
        $user = Auth::user();
        $photo = $request->file('photo') ? Storage::disk('public')->putFile('shops', $request->file('photo')) : null;

        $productArray = [
            'user_id' => $user->id,
            'name' => $request->input('name'),
            'about' => $request->input('about'),
            'buyable' => $request->input('buyable') == 'true' ? true : false,
        ];

        if ($photo) {
            $productArray['photo'] = $photo;
        }

        return $productArray;
    }

    public function store(ApiShopStoreRequest $request): ApiResponse
    {
        if (Auth::user()->hasTelegramAccount() == 0) {
            return ApiResponse::error('shop.create.no_telegram_account');
        }
        $shop = Shop::create($this->prepareShop($request));

        if ($request->unitpay_project_id || $request->unitpay_secretKey) {
            if (UnitpayKey::isKeysUsed($request->unitpay_project_id, $request->unitpay_secretKey)) {
                return ApiResponse::error('validation.unitpay.keys_used');
            }

            $testResult = app(UnitpayPayment::class)->testKeys($request->unitpay_project_id, $request->unitpay_secretKey);
            if ($testResult['success'] === false) {
                return ApiResponse::error($testResult['message']);
            }
            $addKey = $shop->insertUnitpayKey($request->unitpay_project_id,  $request->unitpay_secretKey);
            if ($addKey) {
                $shop->setBuyable(true);
            }
        }

        return ApiResponse::common(ShopResourse::make($shop)->toArray($request));
    }

    private function showList($request): ApiResponse
    {
        $filter = $request->validated();
        $shops = Shop::findByFilter($filter);
        $count = Shop::countByFilter($filter);

        return ApiResponse::listPagination(
                [
                    'Access-Control-Expose-Headers' => 'Items-Count',
                    'Items-Count' => $count
                ]
            )->items((ShopResourse::collection($shops))->toArray($request));
    }

    public function list(ApiShopShowListRequest $request): ApiResponse
    {
        return $this->showList($request);
    }

    public function myList(ApiShopShowMyListRequest $request): ApiResponse
    {
        return $this->showList($request);
    }

    public function show(ApiShopShowRequest $request, $id): ApiResponse
    {
        $shop = Shop::with('legalInfo')->find($id);

        if (CheckShopIsAvailable::isUnavailable($shop)) {
            return ApiResponse::forbidden('common.shop_unavailable');
        }

        return ApiResponse::common(ShopResourse::make($shop)->toArray($request));
    }

    public function destroy(ApiShopDelete $request, int $id): ApiResponse
    {
        Shop::find($id)->delete();

        return ApiResponse::success();
    }

    public function update(ApiShopUpdateRequest $request, int $id): ApiResponse
    {
        $shop = Shop::find($id)->fill($this->prepareShop($request));
        $shop->save();

        return ApiResponse::common(ShopResourse::make($shop)->toArray($request));
    }

    public function sellerConnect(ApiShopShowRequest $request, int $id): ApiResponse
    {
        $tgSeller = Shop::find($id)->getOwnerTg();
        $link = ($tgSeller->user_name ?? false) ? 'https://t.me/' . $tgSeller->user_name : '';
        $userName = $tgSeller->user_name ?? '';

        return ApiResponse::common(['link' => $link, 'user_name' => $userName]); 
    }

    public function getRedirect(Request $request, int $shopId)
    {
        $shop = Shop::find($shopId);
        if (!$shop){
            return \redirect(404);
        }

        if ($shop->photo) {
            $shop->photo = Storage::disk('public')->url($shop->photo);
        }

        log::info('get redirect');
        $link = Shop::buildTgShopLink($shop->id);

        return view('shop_og_info', compact('link', 'shop'));
    }

    public function setPaymentSystem(ApiShopSetPaymentSystemRequest $request): ApiResponse
    {
        $shop = Shop::find($request->shopId);
        $shop->payment_system = $request->payment_system;
        $shop->save();

        return ApiResponse::success();
    }

    public function getPaymentSystems(ApiShopGetPaymentSystemsRequest $request): ApiResponse
    {
        $shop = Shop::find($request->shopId);
        
        return ApiResponse::common($shop->getPaymentSystems());
    }
}