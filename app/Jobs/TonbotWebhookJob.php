<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TonbotWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $payment;
    private $attempt;

    public function __construct($payment, $attempt)
    {
        $this->payment = $payment;
        $this->attempt = $attempt;
    }

    public function handle()
    {
        if (!config('tonbot.webhook_url')) {
            return true;
        }

        $response = Http::post(config('tonbot.webhook_url'), [
            'payment_id' => $this->payment->id,
            'amount' => $this->payment->amount,
            'status' => $this->payment->status,
        ]);

        if (!$response->successful()) {
            $this->attempt++;
            if ($this->attempt < 5) {
                TonbotWebhookJob::dispatch($this->payment, $this->attempt)->delay(now()->addMinutes(15));
            }
        }
    }
}