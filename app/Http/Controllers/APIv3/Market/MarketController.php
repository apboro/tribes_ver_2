<?php

namespace App\Http\Controllers\APIv3\Market;

use App\Http\ApiRequests\Market\ApiBuyProductRequest;
use App\Http\ApiRequests\Market\ApiShowOrderRequest;
use App\Http\ApiResources\AuthorResourse;
use App\Http\ApiResources\Market\ShopOrderResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Market\ShopOrder;
use App\Models\Market\ShopOrderProductList;
use App\Models\Product;
use App\Models\TelegramUser;
use App\Services\Pay\PayService;
use Exception;
use Illuminate\Support\Facades\Log;

class MarketController extends Controller
{
    public function buy(ApiBuyProductRequest $request): ApiResponse
    {
        $tgUser = TelegramUser::provideOneUser($request->getTelegramUserDTO(), $request->getUserDTO());
        $product = Product::find($request->input('product_id'));

        $order = ShopOrder::makeByUser($tgUser, $product, $request->getDeliveryAddress());

        $successUrl = '/market/status?orderId=' . $order->id;
        $url = PayService::buyProduct($order->getPrice(), $order, $tgUser->user, $tgUser->telegram_id, $successUrl);

        return ApiResponse::common(['redirect_url' => $url]);
    }

    public function showOrder(ApiShowOrderRequest $request, int $id): ApiResponse
    {
        $orderCard = ShopOrder::find($id);

        return ApiResponse::common(ShopOrderResource::make($orderCard)->toArray($request));
    }
}
