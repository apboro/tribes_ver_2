<?php

declare(strict_types=1);

namespace App\Bundle\SafeRoute;

use App\Models\Shop\ShopSafeRoute;
use Illuminate\Support\Facades\Log;

class SafeRouteService
{
    public static function build(ShopSafeRoute $safeRoute, array $request): string
    {
        $widgetApi = new SafeRouteWidgetApi();
        $widgetApi->setToken($safeRoute->token);
        $widgetApi->setShopId($safeRoute->safe_shop_id);

        $widgetApi->setMethod($_SERVER['REQUEST_METHOD']);
        $widgetApi->setData($request['data'] ?? []);

        $result = $widgetApi->submit($request['url']);

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //TODO IN work
//            log::info(json_encode($result, JSON_UNESCAPED_UNICODE));
        }

        return $result;
    }
}