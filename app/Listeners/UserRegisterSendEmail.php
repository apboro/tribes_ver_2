<?php

namespace App\Listeners;

use App\Events\ApiUserRegister;
use App\Jobs\SendEmails;
use App\Services\SMTP\Mailer;
use App\Services\TelegramLogService;

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

        SendEmails::dispatch($event->user->email, 'Регистрация', 'Сервис ' . env('APP_NAME'), $v);
    }
}
