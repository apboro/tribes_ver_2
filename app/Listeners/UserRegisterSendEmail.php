<?php

namespace App\Listeners;

use App\Services\SMTP\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserRegisterSendEmail
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $v = view('mail.registration')->with(['login' => $event->user_data->email,'password' => $event->password])->render();
        new Mailer('Сервис ' . env('APP_NAME'), $v, 'Регистрация', $event->user_data->email);
    }
}
