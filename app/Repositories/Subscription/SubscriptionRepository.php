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
    protected TelegramMainBotService $botService;

    public function assignToUser(int $user_id, int $subscription_id)
    {
        $this->botService->sendMessageFromBot(
            config('telegram_bot.bot.botName'),
            472966552,
            'From assign to user'
        );
        new Mailer('Spod', 'From assign to user', 'debug', 'borodachev@gmail.com');

         $userSubscription = UserSubscription::firstOrCreate(
            ['user_id' => $user_id],

            [
                'subscription_id' => $subscription_id,
                'isRecurrent' => true,
                'isActive' => true,
                'expiration_date' => Carbon::now()->addDays(30)
            ]
        );
         $userSubscription->save();
        new Mailer('Spod', 'Must be assigned', 'debug', 'borodachev@gmail.com');

    }

    public function findSubscriptionBySlug($request)
    {
        return Subscription::where('slug', $request['slug'])->get();
    }


}