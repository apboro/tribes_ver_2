<?php

namespace App\Listeners;

use App\Events\TariffPayedEvent;
use App\Http\ApiResponses\ApiResponse;
use App\Jobs\SendEmails;
use App\Models\Tariff;
use Exception;
use Illuminate\Support\Facades\Log;

class TariffPayedListener
{
    public function __construct()
    {

    }

    public function handle(TariffPayedEvent $event): void
    {
        if ($event->payment->comment == 'trial'){
            $variant = $event->payment->community->tariff->getTrialVariant();
            $v = view('mail.telegram_invitation_trial')->withPayment($event->payment)->withVariant($variant)->render();
        } else {
            $v = view('mail.telegram_invitation')->withPayment($event->payment)->render();
        }
        try {
            SendEmails::dispatch($event->user->email, 'Приглашение', 'Сервис ' . config('app.name'), $v);
        } catch (Exception $e) {
            Log::channel('daily')->error('Send Tariff Payed email error', [$e]);
        }
    }
}
