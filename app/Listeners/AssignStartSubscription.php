<?php

namespace App\Listeners;

use App\Events\ApiUserRegister;
use App\Models\Subscription;
use App\Repositories\Subscription\SubscriptionRepository;
use Exception;
use Log;


class AssignStartSubscription
{

    private SubscriptionRepository $subscriptionRepository;

    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function handle(ApiUserRegister $event)
    {
        log::info('AssignStartSubscription handler run');
        /** @var Subscription|null $subscription */
        $subscription = Subscription::query()->where('slug', 'trial_plan')->first();
        try {
            $this->subscriptionRepository->assignToUser($event->user->id, $subscription->id);
        } catch (Exception $e) {
            log::error('AssignStartSubscription listener:' . json_encode($e, JSON_UNESCAPED_UNICODE));
            return $e;
        }
    }

}