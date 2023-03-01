<?php

namespace App\Listeners;

use App\Events\FeedBackCreate;
use App\Services\SMTP\Mailer;

class FeedBackListener
{
    /**
     * Handle the event.
     *
     * @param  FeedBackCreate  $event
     * @return void
     */
    public function handle(FeedBackCreate $event): void
    {
        $v = view('mail.feedback_create')->with(
            [
                'message' => $event->feedback->text,
            ]
        )->render();

        new Mailer('Сервис ' . env('APP_NAME'), $v, 'Вы задали вопрос ', $event->user->email);
    }
}
