<?php

namespace App\Http\Controllers\APIv3\Shop;

use App\Http\ApiRequests\Shop\ApiLegalShop;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\User\UserLegalInfo;

class ApiShopLegalController extends Controller
{
    public function privacy(ApiLegalShop $request, int $shopId): ApiResponse
    {
        $legal =  $this->findLegal($shopId);
        if (!$legal) {
            ApiResponse::error('common.error');
        }
        $url = $this->getAppUrl($shopId);

        return ApiResponse::common(view('legal.privacy', ['legal' => $legal, 'url' => $url])->render());
    }

    public function offer(ApiLegalShop $request, int $shopId): ApiResponse
    {
        $legal =  $this->findLegal($shopId);
        if (!$legal) {
            ApiResponse::error('common.error');
        }
        $url = $this->getAppUrl($shopId);

        return ApiResponse::common(view('legal.offer', ['legal' => $legal, 'url' => $url])->render());
    }

    private function findLegal(int $shopId): ?UserLegalInfo
    {
        return Shop::find($shopId)->user->legalInfoFirst();
    }

    private function getAppUrl(int $shopId): string
    {
        return 'https://t.me/' . config('telegram_bot.bot.botName') . '/' . config('telegram_bot.bot.marketName') .'?startapp=' . $shopId;
    }

}