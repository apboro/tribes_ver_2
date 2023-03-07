<?php

namespace App\Http\Controllers\APIv3;

use App\Events\ApiUserRegister;
use App\Events\SubscriptionMade;
use App\Http\ApiRequests\ApiAssignSubscriptionRequest;
use App\Http\ApiRequests\ApiSubscriptionPayRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\ApiResponses\ApiResponseError;
use App\Http\ApiResponses\ApiResponseSuccess;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserSubscriptionRequest;
use App\Http\Requests\UpdateUserSubscriptionRequest;
use App\Models\Course;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSubscription;
use App\Repositories\Subscription\SubscriptionRepository;
use App\Repositories\Tariff\TariffRepository;
use App\Services\TelegramLogService;
use App\Services\Tinkoff\Payment as TinkoffPayment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

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
     * TODO Listener
     *
     * @param ApiAssignSubscriptionRequest $request
     * @return ApiResponseError|ApiResponseSuccess
     */
    public function assignSubscriptionToUser(ApiAssignSubscriptionRequest $request)
    {
        $user = Auth::user();
        if ($user->subscription) {
            return ApiResponse::error('subscription.already_subscribed');
        }
        $subscription = Subscription::find($request['subscription_id']);
        if ($subscription) {
            $this->subscriptionRepository->assignToUser($user->id, $subscription->id);
        }

        return ApiResponse::success('subscription.successfully_subscribed');
    }

    public function payForSubscription(ApiSubscriptionPayRequest $request)
    {
        /** @var User $user */

        $user = Auth::user();
        $subscription = Subscription::find($request->input('subscription_id'));

        if ($subscription === null) {
            return ApiResponse::notFound('subscription.required');
        }

        $payment = $this->tinkoff_payment->doPayment($user, $subscription, $subscription->price * 100);

        if ($payment === false) {
            $this->telegramLogService->sendLogMessage(
                'При инициализации оплаты тарифа произошла ошибка Payment'
            );
            return ApiResponse::error('common.error_while_pay');
        }

        $this->subscriptionRepository->assignToUser($user->id, $subscription->id);

//          TODO move assignment to listener
//        Event::dispatch(new SubscriptionMade($user, $subscription));

        return ApiResponse::common([
            'redirect' => $payment->paymentUrl
        ]);

    }

}
