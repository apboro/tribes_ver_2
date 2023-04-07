<?php

namespace App\Listeners;

use App\Events\SubscriptionMade;
use App\Repositories\Subscription\SubscriptionRepository;
use App\Services\SMTP\Mailer;
use App\Services\TelegramMainBotService;

class SubscriptionListener
{
    private SubscriptionRepository $subscriptionRepository;

    public function __construct(SubscriptionRepository $subscriptionRepository)
        {
            $this->subscriptionRepository = $subscriptionRepository;
        }

    /**
     * Handle the event.
     *
     * @param  \App\Events\SubscriptionMade  $event
     * @return void
     */
    public function handle(SubscriptionMade $event)
    {
        $this->subscriptionRepository->assignToUser($event->user->id, $event->subscription->id);
    }
}
