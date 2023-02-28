<?php

namespace App\Events;

use App\Models\Community;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateCommunity
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Community $community;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Community $community)
    {

        $this->community = $community;
    }

}
