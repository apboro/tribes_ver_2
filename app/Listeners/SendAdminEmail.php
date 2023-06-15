<?php

namespace App\Listeners;

use App\Events\UserDeleteEvent;
use App\Services\SMTP\Mailer;

class SendAdminEmail
{

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(UserDeleteEvent $event)
    {
        $v = view('mail.user_delete')->with(['user_id' => $event->user->id])->render();
        new Mailer('Сервис ' . env('APP_NAME'), $v, 'Удаление пользователя', 'info@spodial.com');
    }
}
