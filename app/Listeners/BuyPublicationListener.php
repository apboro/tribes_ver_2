<?php

namespace App\Listeners;

use App\Events\BuyCourse;
use App\Events\BuyPublicaionEvent;
use App\Jobs\SendEmails;
use App\Services\SMTP\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BuyPublicationListener
{
    public function handle(BuyPublicaionEvent $event)
    {
        $v = view('mail.publication_thanks_buyer', ['publication' => $event->publication])->render();
        SendEmails::dispatch($event->user->email, 'Доступ к публикации успешно оплачен', 'Сервис ' . config('app.name'), $v);
    }
}
