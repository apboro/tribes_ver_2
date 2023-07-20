<?php

namespace App\Events;

use App\Models\Publication;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BuyPublicaionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * @var Publication $publication
     */
    public Publication $publication;

    /**
     * @var User $user
     */

    public User $user;

    /**
     * Create a new event instance.
     *
     * @param Publication $publication
     * @param User $user
     */

    public function __construct(Publication $publication,User $user)
    {
        $this->publication = $publication;
        $this->user = $user;
    }
}
