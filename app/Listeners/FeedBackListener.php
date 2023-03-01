<?php

namespace App\Listeners;

use App\Events\FeedBackCreate;
use App\Services\SMTP\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class FeedBackListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  FeedBackCreate  $event
     * @return void
     */
    public function handle(FeedBackCreate $event)
    {
        $v = view('mail.feedback_create')->with(
            [
                'message' => $event->feedback->text,
            ]
        )->render();

        new Mailer('Сервис ' . env('APP_NAME'), $v, 'Вы задали вопрос ', $event->user->email);
    }
}
