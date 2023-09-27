<?php

namespace App\Listeners;

use App\Events\TariffPayedEvent;
use App\Http\ApiResponses\ApiResponse;
use App\Jobs\SendEmails;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Services\Tariff\TariffPayedService;

class TariffPayedListener
{
    private $tariffPayedService;

    public function __construct(TariffPayedService $tariffPayedService)
    {
        $this->tariffPayedService = $tariffPayedService;
    }

    public function handle(TariffPayedEvent $event): void
    {
        try {
            $this->tariffPayedService->newOrExtend($event->payment);
        } catch (Exception $e) {
            Log::channel('daily')->error('Send Tariff Payed email error', [$e]);
        }
    }
}
