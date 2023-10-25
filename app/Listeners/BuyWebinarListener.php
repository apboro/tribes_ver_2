<?php

namespace App\Listeners;

use App\Events\BuyCourse;
use App\Events\BuyWebinarEvent;
use App\Jobs\SendEmails;
use App\Services\SMTP\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BuyWebinarListener
{
    public function handle(BuyWebinarEvent $event)
    {
        $v = view('mail.webinar_thanks_buyer', ['webinar' => $event->webinar])->render();
        SendEmails::dispatch($event->user->email, 'Доступ к вебинару успешно оплачен', 'Сервис ' . config('app.name'), $v);
    }
}
