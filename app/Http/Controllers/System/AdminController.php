<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\UserSubscription;
use Auth;
use Illuminate\Support\Carbon;

/**
 *  TODO one-time functionality, otherwise do it through the admin panel
 */
final class AdminController extends Controller
{
    public const TRIAL_PERIOD_ID = 1;
    public const PAY_PERIOD_ID = 2;

    /**
     * TODO move to configs
     *
     * @var array
     */
    private const SUBSCRIPTION_PLANS = [
        self::TRIAL_PERIOD_ID => [
            'name'        => 'Пробный период',
            'slug'        => 'trial_plan',
            'description' => '[{"name": "Управление сообществами","description" : null},{"name": "Создание курсов (LMS)","description": null},{"name": "Аналитика сообществ","description": null},{"name": "Комиссия с продаж","description":"15%"}]',
            'is_active'   => true,
            'price'       => 0,
            'period_days' => 30,
            'sort_order'  => 1,
            'commission'  => 15,
            'file_id'     => null,
        ],
        self::PAY_PERIOD_ID   => [
            'name'        => 'Платный период',
            'slug'        => 'pay_plan',
            'description' => '[{"name": "Управление сообществами","description" : null},{"name": "Создание курсов (LMS)","description": null},{"name": "Аналитика сообществ","description": null},{"name": "Комиссия с продаж","description":"10%"}]',
            'is_active'   => true,
            'price'       => 500,
            'period_days' => 30,
            'sort_order'  => 1,
            'commission'  => 10,
            'file_id'     => null,
        ]
    ];

    /**
     * Lazy
     *
     * @return void
     */
    public function resetTrialForAllUsers()
    {
        /** @var User $user */
        $user = Auth::user();
        if($user->email !== 'suppport@tribes.fabit.ru') {
            die('нет прав');
        }

        $userSubscriptionList = UserSubscription::all();
        $date = Carbon::now();
        $date->addDays(30);

        foreach($userSubscriptionList as $userSubscription) {
            $userSubscription->expiration_date = $date->timestamp;
            $userSubscription->save();
        }

        dd(count($userSubscriptionList));
    }

    /**
     * Lazy update subscription plans
     *
     * @return void
     */
    public function updateSubscriptionPlans()
    {
        $user = Auth::user();
        if($user->email !== 'suppport@tribes.fabit.ru') {
            die('нет прав');
        }

        foreach(self::SUBSCRIPTION_PLANS as $planId => $planInfo) {
            /** @var Subscription $subscription */
            $subscription = Subscription::where('id', $planId)->firstOrFail();
            $subscription->update($planInfo);
        }

        dd(Subscription::all());
    }
}
