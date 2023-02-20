<?php

namespace App\Listeners;

use App\Events\ApiUserRegister;
use App\Services\SMTP\Mailer;

class UserRegisterSendEmail
{
    /**
     * Handle the event.
     *
     * @param ApiUserRegister $event
     *
     * @return void
     */
    public function handle(ApiUserRegister $event): void
    {
        $v = view('mail.registration')->with(['login' => $event->user->email, 'password' => $event->password])->render();

        new Mailer('Сервис ' . env('APP_NAME'), $v, 'Регистрация', $event->user->email);
    }
}
