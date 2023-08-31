<?php

namespace App\Events;

use App\Models\Webinar;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BuyWebinarEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Webinar $webinar;
    public User $user;

    public function __construct(Webinar $webinar,User $user)
    {
        $this->webinar = $webinar;
        $this->user = $user;
    }
}
