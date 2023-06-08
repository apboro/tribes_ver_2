<?php

namespace App\Http\Controllers\APIv3\Payments;

use App\Events\SubscriptionMade;
use App\Helper\PseudoCrypt;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Repositories\Payment\PaymentRepository;
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use App\Services\Tinkoff\TinkoffService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class ApiPaymentController extends Controller
{
    private $paymentRepo;
    protected TelegramMainBotService $botService;
    private TelegramLogService $telegramLogService;
    private TinkoffService $tinkoff;


    public function __construct(
        PaymentRepository      $paymentRepo,
        TelegramMainBotService $botService,
        TelegramLogService     $telegramLogService
    )
    {
        $this->botService = $botService;
        $this->paymentRepo = $paymentRepo;
        $this->telegramLogService = $telegramLogService;
        $this->tinkoff = new TinkoffService();
    }

    //TODO Tests
    public function successPayment(Request $request, $hash, $telegramId = NULL, $successUrl = null)
    {
        $payment = Payment::find(PseudoCrypt::unhash($hash));
        if ($payment->status === 'CONFIRMED') {
            Event::dispatch(new SubscriptionMade($payment->payer, $payment->payable));
        }
        $redirectUrl = $successUrl ?? env('FRONTEND_URL').'/app/subscriptions?payment_result=success';
        Log::debug('successPayment $redirectUrl - '. $redirectUrl);

        return redirect($redirectUrl);
    }

}