<?php

namespace App\Repositories\Subscription;

use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSubscription;


class SubscriptionRepository
{
    public function assignToUser(int $user_id, int $subscription_id)
    {
        $subscription = new UserSubscription();
        $subscription->user_id = $user_id;
        $subscription->subscription_id = $subscription_id;
        $subscription->isRecurrent = true;
        $subscription->save();
    }

    public function findSubscriptionBySlug($request)
    {
        return Subscription::where('slug', $request['slug'])->get();
    }




}