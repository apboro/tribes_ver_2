<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChatUserJoin
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $chat_id;
    public int $telegram_user_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $chat_id, int $telegram_user_id)
    {
        //
        $this->chat_id = $chat_id;
        $this->telegram_user_id = $telegram_user_id;
    }

}
