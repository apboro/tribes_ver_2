<?php

namespace App\Repositories\Subscription;

use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSubscription;
use Carbon\Carbon;


class SubscriptionRepository
{
    public function assignToUser(int $user_id, int $subscription_id)
    {
        $subscription = UserSubscription::where('user_id', $user_id)->first();
        $subscription->subscription_id = $subscription_id;
        $subscription->isRecurrent = true;
        $subscription->isActive = true;
        $subscription->expiration_date = Carbon::now()->addDays(30);
        $subscription->save();
    }

    public function findSubscriptionBySlug($request)
    {
        return Subscription::where('slug', $request['slug'])->get();
    }


}