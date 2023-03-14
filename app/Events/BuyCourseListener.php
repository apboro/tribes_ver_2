<?php

namespace App\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Services\SMTP\Mailer;

class BuyCourseListener
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public function handle(BuyCourse $event){
        $v = view('mail.media_thanks_buyer')->withCourse($event->course)->render();
        new Mailer(
            'Сервис Spodial',
            $v, 'Покупка ' .  $event->course->title,
            $event->user->email
        );

        if($event->course->shipping_noty){
            $v = view('mail.media_thanks_author')->withCourse($event->course)->render();

            new Mailer('Сервис Spodial',
                $v,
                'Покупка ' .  $event->course->title,
                $event->course->author()->first()->email
            );
        }
    }


}
