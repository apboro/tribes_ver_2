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
use App\Services\Telegram\MainBot;
use App\Services\Telegram\MainBotCollection;
use Exception;
use Illuminate\Support\Facades\Log;

class MarketController extends Controller
{
    private MainBotCollection $mainBot;

    public function __construct(MainBotCollection $mainBot)
    {
       $this->mainBot = $mainBot;
    }

    public function buy(ApiBuyProductRequest $request): ApiResponse
    {
        $tgUser = TelegramUser::provideOneUser($request->getTelegramUserDTO(), $request->getUserDTO());
        $product = Product::find($request->input('product_id'));

        $order = ShopOrder::makeByUser($tgUser, $product, $request->getDeliveryAddress());

        if ($request->input('is_mobile')) {
            $successUrl = $order->id;
        } else {
            $successUrl = '/market/status/' . $order->id;
        }

        $payment = PayService::buyProduct($order->getPrice(), $order, $tgUser->user, $tgUser->telegram_id, $successUrl);

        $this->sendNotifications($tgUser, $product, $order);

        if ($payment === false) {
            return ApiResponse::error('common.error_while_pay');
        }

        return ApiResponse::common(['redirect' => $payment->paymentUrl]);
    }

    public function showOrder(ApiShowOrderRequest $request, int $id): ApiResponse
    {
        $orderCard = ShopOrder::find($id);

        return ApiResponse::common(ShopOrderResource::make($orderCard)->toArray($request));
    }

    private function sendNotifications(TelegramUser $tgUser, Product $product, ShopOrder $order)
    {
        $message = 'Заказа товара: ' . $product->title;
        $messageBayer = 'Вы оплатили заказ - '. $product->title .' номер заказа: ' . $order->id;
        $telegramId = $product->author->user->telegramMeta->first()->telegram_id;

        $mainBot = $this->mainBot->getBotByName('spodial_test_bot');
        $mainBot->getExtentionApi()->sendMess($telegramId, $message);
        $mainBot->getExtentionApi()->sendMess($tgUser->telegram_id, $messageBayer);
    }
}
