<?php

namespace App\Http\Controllers\APIv3\Subscription;

use App\Http\ApiRequests\ApiAssignSubscriptionRequest;
use App\Http\ApiRequests\Subscription\ApiChangeSubscriptionRecurrentRequest;
use App\Http\ApiRequests\Subscription\ApiSubscriptionPayRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\ApiResponses\ApiResponseCommon;
use App\Http\ApiResponses\ApiResponseError;
use App\Http\ApiResponses\ApiResponseSuccess;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSubscription;
use App\Repositories\Subscription\SubscriptionRepository;
use App\Services\TelegramLogService;
use Illuminate\Support\Facades\Auth;
use App\Services\Pay\PayService;

class ApiUserSubscriptionController extends Controller
{
    private SubscriptionRepository $subscriptionRepository;

    private TelegramLogService $telegramLogService;

    public function __construct(
        SubscriptionRepository $subscriptionRepository,
        TelegramLogService $telegramLogService
    )
    {
        $this->subscriptionRepository = $subscriptionRepository;
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
        $subscriptionType = $request->input('subscription_id');

        $userSubscription = UserSubscription::where('user_id', '=', $user->id)
                            ->where('subscription_id', '=', $subscriptionType)
                            ->where('expiration_date', '>' , time())->first();

        if($userSubscription) {
            return ApiResponse::error('Тариф активен');
        }

        $subscription = Subscription::find($subscriptionType);

        $success_url = $request->success_url ?? null;

        $payment = PayService::buySubscription($subscription->price, $subscription, $user, $success_url);

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
