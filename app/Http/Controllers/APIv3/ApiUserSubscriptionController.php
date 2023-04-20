<?php

namespace App\Http\Controllers\APIv3;

use App\Http\ApiRequests\Subscription\ApiChangeSubscriptionRecurrentRequest;
use App\Http\ApiRequests\Subscription\ApiSubscriptionPayRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\ApiResponses\ApiResponseCommon;
use App\Http\ApiResponses\ApiResponseError;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use App\Repositories\Subscription\SubscriptionRepository;
use App\Services\TelegramLogService;
use App\Services\Tinkoff\Payment as TinkoffPayment;
use Illuminate\Support\Facades\Auth;


class ApiUserSubscriptionController extends Controller
{
    private SubscriptionRepository $subscriptionRepository;

    private TinkoffPayment $tinkoff_payment;

    private TelegramLogService $telegramLogService;

    public function __construct(
        SubscriptionRepository $subscriptionRepository,
        TinkoffPayment         $tinkoff_payment,
        TelegramLogService $telegramLogService
    )
    {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->tinkoff_payment = $tinkoff_payment;
        $this->telegramLogService = $telegramLogService;
    }

    /**
     * TODO tests
     */
    public function payForSubscription(ApiSubscriptionPayRequest $request)
    {
        /** @var User $user */

        $user = Auth::user();

        $subscription = Subscription::find($request->input('subscription_id'));

        $payment = $this->tinkoff_payment->doPayment($user, $subscription, $subscription->price);

        if ($payment === false) {
            $this->telegramLogService->sendLogMessage(
                'При инициализации оплаты подписки произошла ошибка Payment'
            );
            return ApiResponse::error('subscription.payment_error');
        }

        return ApiResponse::common([
            'redirect' => $payment->paymentUrl
        ]);
    }

    /**
     * TODO Tests
     * @param ApiChangeSubscriptionRecurrentRequest $request
     * @return ApiResponseCommon|ApiResponseError
     */
    public function changeRecurrent(ApiChangeSubscriptionRecurrentRequest $request)
    {
        $user=Auth::user();
        $subscription = $user->subscription;
        if ($subscription) {
            $subscription->isRecurrent = !$subscription->isRecurrent;
            $subscription->save();
            return ApiResponse::common($subscription);
        }
        return ApiResponse::error('subscription.required');
    }

}
