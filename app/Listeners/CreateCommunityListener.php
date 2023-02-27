<?php

namespace App\Listeners;

use App\Events\CreateCommunity;
use App\Services\Telegram;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateCommunityListener
{
    private Telegram $telegram;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Telegram $telegram)
    {
        //
        $this->telegram = $telegram;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CreateCommunity $event)
    {
        $this->telegram->createCommunity($event->community);
    }
}
