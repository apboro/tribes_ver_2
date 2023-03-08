<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FeedBackAnswer
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public User $user;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user,string $answer)
    {
        $this->user = $user;
        $this->answer = $answer;
    }

}
