<?php

namespace App\Repositories\Subscription;

use App\Models\Subscription;
use App\Models\User;
use App\Models\UserSubscription;
use App\Services\SMTP\Mailer;
use App\Services\TelegramMainBotService;
use Carbon\Carbon;
use Log;

class SubscriptionRepository
{
    public function assignToUser(int $user_id, int $subscription_id)
    {
        log::info('assignToUser user id:' . $user_id . 'sub_id: ' . $subscription_id);

        $subscription = Subscription::find($subscription_id);
        $recurrent = $this->isRecurrent($subscription);
        $expirationDate = $this->getExpirationDate($subscription);

        UserSubscription::updateOrCreate(
            ['user_id' => $user_id],
            [
                'subscription_id' => $subscription_id,
                'expiration_date' => $expirationDate,
                'isActive' => true,
                'isRecurrent' => $recurrent
            ]
        );
    }

    public function findSubscriptionBySlug($request)
    {
        return Subscription::where('slug', $request['slug'])->get();
    }

    private function isRecurrent(Subscription $subscription): bool
    {
        return $subscription->price > 0 ? true : false;
    }

    private function getExpirationDate(Subscription $subscription): int
    {
        $days = (int) $subscription->period_days ?? env('SUBSCRIPTION_PERIOD', 30);

        return Carbon::now()->addDays($days)->timestamp;
    }
}