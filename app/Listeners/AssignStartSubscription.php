<?php

namespace App\Listeners;

use App\Events\ApiUserRegister;
use App\Models\Subscription;
use App\Repositories\Subscription\SubscriptionRepository;
use Exception;


class AssignStartSubscription
{

    private SubscriptionRepository $subscriptionRepository;

    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function handle(ApiUserRegister $event)
    {
        /** @var Subscription|null $subscription */
        $subscription = Subscription::query()->where('slug', 'trial_plan')->first();
        try {
            $this->subscriptionRepository->assignToUser($event->user->id, $subscription->id);
        } catch (Exception $e) {
            return $e;
        }
    }

}