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
         UserSubscription::firstOrCreate(
            ['user_id' => $user_id],

            [
                'subscription_id' => $subscription_id,
                'isRecurrent' => true,
                'isActive' => true,
                'expiration_date' => Carbon::now()->addDays(30)
            ]
        );
    }

    public function findSubscriptionBySlug($request)
    {
        return Subscription::where('slug', $request['slug'])->get();
    }


}