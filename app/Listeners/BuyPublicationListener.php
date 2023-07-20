<?php

namespace App\Listeners;

use App\Events\BuyCourse;
use App\Events\BuyPublicaionEvent;
use App\Services\SMTP\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BuyPublicationListener
{
    public function handle(BuyPublicaionEvent $event){
        $v = view('mail.media_thanks_buyer')->withCourse($event->publication)->render();
        new Mailer(
            'Сервис Spodial',
            $v, 'Покупка ' .  $event->publication->title,
            $event->user->email
        );
    }
}
