<?php

namespace App\Http\Controllers\APIv3\Market;

use App\Http\ApiRequests\Market\ApiBuyProductRequest;
use App\Http\ApiRequests\Market\ApiShowOrderRequest;
use App\Http\ApiRequests\Market\ShopCardDeleteRequest;
use App\Http\ApiRequests\Market\ShopCardListRequest;
use App\Http\ApiRequests\Market\ShopCardUpdateRequest;
use App\Http\ApiResources\Market\ShopOrderResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Market\ShopCard;
use App\Models\Market\ShopDelivery;
use App\Models\Market\ShopOrder;
use App\Models\Product;
use App\Models\TelegramUser;
use App\Services\Pay\PayService;
use App\Services\Telegram\MainBotCollection;
use Exception;
use Log;

class MarketController extends Controller
{
    public const SUPPORT_TELEGRAM_USER_ID = 427143658;

    private MainBotCollection $mainBot;

    public function __construct(MainBotCollection $mainBot)
    {
        $this->mainBot = $mainBot;
    }

    public function create(ApiBuyProductRequest $request): ApiResponse
    {
        try {
            $email = $request->input('email');
            $phone = $request->input('phone');
            $shopId = $request->input('shop_id');

            $order = $this->makeOrder($request, $phone, $shopId);
            $order->setStatus(ShopOrder::TYPE_NOT_BUYBLE);

            if ($order === false) {
                return ApiResponse::error('common.create_error');
            }

            $this->sendNotifications($order, $email);

            return ApiResponse::common(['order_id' => $order->id]);
        } catch (Exception $e) {
            $message = $e->getMessage();
            log::error('Shop Card empty:' . $message);

            return ApiResponse::error('common.create_error');
        }
    }

    private function makeOrder(ApiBuyProductRequest $request, string $phone, int $shopId)
    {
        $tgUser = TelegramUser::provideOneUser($request->getTelegramUserDTO(), $request->getUserDTO());
        $shopDelivery = ShopDelivery::makeByUser($tgUser, $request->getDeliveryAddress(), $phone);
        $productsList = $request->getProductIdList();

        $shopOrder = ShopOrder::makeByUser($tgUser, $shopDelivery);

        return ShopCard::transferProductsToOrder($shopOrder, $shopId, $tgUser->telegram_id, $productsList);
    }

//    public function buy(ApiBuyProductRequest $request): ApiResponse
//    {
//        try {
//            $tgUser = TelegramUser::provideOneUser($request->getTelegramUserDTO(), $request->getUserDTO());
//
//            $phone = $request->getUserDTO()['phone'];
//            $shopId = $request->input('shop_id');
//            $productIdList = $request->getProductIdList();
//            $shopDelivery = ShopDelivery::makeByUser($tgUser, $request->getDeliveryAddress(), $phone);
//
//            $order = ShopOrder::makeByUser($tgUser, $shopDelivery);
//            $order = $this->makeOrder($request, $phone, $shopId);
//            $order->setStatus(ShopOrder::TYPE_BUYBLE);
//
//            if ($request->input('is_mobile')) {
//                $successUrl = $order->id;
//            } else {
//                $successUrl = '/market/status/' . $order->id;
//            }
//
//            $payment = PayService::buyProduct($order->getPrice($shopId, $this->input('telegram_user_id')),
//                $order, $tgUser->user, $tgUser->telegram_id, $successUrl);
//
////        $this->sendNotifications($tgUser, $product, $order);
//
//            if ($payment === false) {
//                return ApiResponse::error('common.error_while_pay');
//            }
//
//            return ApiResponse::common(['redirect' => $payment->paymentUrl]);
//        } catch (Exception $e) {
//            $message = $e->getMessage();
//            log::error('Shop Card empty:' . $message);
//
//            return ApiResponse::error('common.create_error');
//        }
//    }

    public function showOrder(ApiShowOrderRequest $request, int $id): ApiResponse
    {
        $orderCard = ShopOrder::getOrder($id);

        return ApiResponse::common(ShopOrderResource::make($orderCard)->toArray($request));
    }

    public function shopOrdersHistory(ShopCardListRequest $request): ApiResponse
    {
        $orderCard = ShopOrder::getHistory($request->getShopId(), $request->getTgUserId());

        return ApiResponse::common($orderCard->toArray());
    }

    public function deleteCardProduct(ShopCardDeleteRequest $request): ApiResponse
    {
        ShopCard::where([
            'id'               => $request->input('id'),
            'telegram_user_id' => $request->input('telegram_user_id'),
            'shop_id'          => $request->input('shop_id'),
        ])->delete();

        return ApiResponse::success('common.success');
    }

    public function getCard(ShopCardListRequest $request): ApiResponse
    {
        $userCard = ShopCard::with('product')->where([
            'shop_id'          => $request->getShopId(),
            'telegram_user_id' => $request->getTgUserId()
        ])->get()->toArray();

        return ApiResponse::common($userCard);
    }

    public function updateCard(ShopCardUpdateRequest $request): ApiResponse
    {
        ShopCard::cardUpdateOrCreate($request->validated());

        return ApiResponse::success('common.success');
    }

    private function sendNotifications(ShopOrder $order, string $email)
    {
        $shop = $order->getShop();
        $shopOwnerTgId = $shop->getOwnerTg()->telegram_id ?? self::SUPPORT_TELEGRAM_USER_ID;

        $mainBot = $this->mainBot->getBotByName(config('telegram_bot.bot.botName'));

        $messageOwner = ShopOrder::prepareMessageToOwner($order, $email);
        $messageBayer = ShopOrder::prepareMessageToBayer($order);

        $clientTelegramId = $order->telegramMeta->telegram_id;
        log::info('send  natify to telegram ids clent:' . $clientTelegramId . ' shop owner:' . $shopOwnerTgId);

        $mainBot->getExtentionApi()->sendMess($shopOwnerTgId, $messageOwner);
        $mainBot->getExtentionApi()->sendMess($clientTelegramId, $messageBayer);
    }
}
