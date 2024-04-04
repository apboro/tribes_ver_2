<?php

namespace App\Http\Controllers\APIv3\Shop;

use App\Http\ApiRequests\Shop\ApiShopDelete;
use App\Http\ApiRequests\Shop\ApiShopShowRequest;
use App\Http\ApiRequests\Shop\ApiShopShowListRequest;
use App\Http\ApiRequests\Shop\ApiShopShowMyListRequest;
use App\Http\ApiRequests\Shop\ApiShopStoreRequest;
use App\Http\ApiRequests\Shop\ApiShopUpdateRequest;
use App\Http\ApiResources\ShopResourse;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
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
            $shop->insertUnitpayKey($request->unitpay_project_id,  $request->unitpay_secretKey);
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

        return ApiResponse::common(['link' => $link]); 
    }   
}