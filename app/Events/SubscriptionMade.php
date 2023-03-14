<?php

namespace App\Events;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubscriptionMade
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public Subscription $subscription;

    public function __construct(User $user, Subscription $subscription)
    {
        $this->subscription = $subscription;
        $this->user = $user;
    }

}
