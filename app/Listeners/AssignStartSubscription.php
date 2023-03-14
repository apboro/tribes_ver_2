<?php

namespace App\Listeners;

use App\Events\ApiUserRegister;
use App\Models\Subscription;
use App\Repositories\Subscription\SubscriptionRepository;


class AssignStartSubscription
{

    private SubscriptionRepository $subscriptionRepository;

    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function handle(ApiUserRegister $event)
    {
        $this->subscriptionRepository->assignToUser($event->user->id, Subscription::where('slug', 'start')->first()->id);
    }

}