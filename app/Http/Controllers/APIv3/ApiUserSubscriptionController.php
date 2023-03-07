<?php

namespace App\Http\Controllers\APIv3;

use App\Events\ApiUserRegister;
use App\Http\ApiRequests\ApiAssignSubscriptionRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserSubscriptionRequest;
use App\Http\Requests\UpdateUserSubscriptionRequest;
use App\Models\Subscription;
use App\Models\UserSubscription;
use App\Repositories\Subscription\SubscriptionRepository;
use App\Repositories\Tariff\TariffRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

class ApiUserSubscriptionController extends Controller
{
    private SubscriptionRepository $subscriptionRepository;

    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * TODO tests
     *
     * @param ApiAssignSubscriptionRequest $request
     * @return \App\Http\ApiResponses\ApiResponseError|\App\Http\ApiResponses\ApiResponseSuccess
     */
    public function assignSubscriptionToUser(ApiAssignSubscriptionRequest $request)
    {
        $user = Auth::user();
        if ($user->subscription) {
            return ApiResponse::error('subscription.already_subscribed');
        }
        $subscription = Subscription::find($request['subscription_id']);
        if ($subscription){
            $this->subscriptionRepository->assignToUser($user->id, $subscription->id);
        }

//        Event::dispatch(new Su);

        return ApiResponse::success('subscription.successfully_subscribed');
    }

    public function payForSubscription()
    {

    }

}
