<?php

namespace App\Repositories\Subscription;

use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSubscription;
use App\Services\SMTP\Mailer;
use App\Services\TelegramMainBotService;
use Carbon\Carbon;


class SubscriptionRepository
{

    public function getDays(int $subscriptionId)
    {
        $subscription = Subscription::find($subscriptionId);

        return (int) $subscription->period_days ?? env('SUBSCRIPTION_PERIOD', 30);
    }

    public function assignToUser(int $user_id, int $subscription_id)
    {
        $days = $this->getDays($subscription_id);

        $userSubscription = UserSubscription::firstOrNew(['user_id' => $user_id]);
        $userSubscription->subscription_id = $subscription_id;
        $userSubscription->expiration_date = Carbon::now()->addDays($days)->timestamp;
        $userSubscription->save();
    }

    public function findSubscriptionBySlug($request)
    {
        return Subscription::where('slug', $request['slug'])->get();
    }


}