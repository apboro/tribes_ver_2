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

    public function create(ApiBuyProductRequest $request): ApiResponse
    {
        $order = $this->makeOrder($request);

        if ($order === false) {
            return ApiResponse::error('common.error_while_pay');
        }

        $this->sendNotifications($order);

        return ApiResponse::common(['order_id' => $order->id]);
    }

    private function makeOrder(ApiBuyProductRequest $request)
    {
        $tgUser = TelegramUser::provideOneUser($request->getTelegramUserDTO(), $request->getUserDTO());
        $product = Product::find($request->input('product_id'));

        return ShopOrder::makeByUser($tgUser, $product, $request->getDeliveryAddress());
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

        $payment = PayService::buyProduct($order->getPrice(),
            $order, $tgUser->user, $tgUser->telegram_id, $successUrl);

//        $this->sendNotifications($tgUser, $product, $order);

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

    private function sendNotifications(ShopOrder $order)
    {
        $telegram = $order->products->first()->author->user->telegramMeta->first();
        $telegramId = $telegram ? $telegram->telegram_id : 427143658;

        $mainBot = $this->mainBot->getBotByName(config('telegram_bot.bot.botName'));

        $messageOwner = ShopOrder::prepareMessageToOwner($order);
        $messageBayer = ShopOrder::prepareMessageToBayer($order);

        $mainBot->getExtentionApi()->sendMess($telegramId, $messageOwner);
        $mainBot->getExtentionApi()->sendMess($order->telegramMeta->telegram_id, $messageBayer);
    }
}
