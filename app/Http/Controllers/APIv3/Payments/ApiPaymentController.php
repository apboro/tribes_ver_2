<?php

namespace App\Http\Controllers\APIv3\Payments;

use App\Helper\PseudoCrypt;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Repositories\Payment\PaymentRepository;
use App\Services\PaymentRedirectService;
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use App\Services\Tinkoff\TinkoffService;
use Illuminate\Http\Request;
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

    public function successPayment(Request $request, $hash)
    {
        $payment = Payment::find(PseudoCrypt::unhash($hash));
        $redirectUrl = '';

        if ($payment->status !== 'CONFIRMED') {
            $redirectUrl = config('app.frontend_url') . '/status/' . $payment->id; 
            Log::debug('successPayment $redirectUrl - ' . $redirectUrl);
            return redirect($redirectUrl);
        }

        $redirectUrl = PaymentRedirectService::buildSuccessUrl($payment, $request->success_url);

        Log::debug('successPayment $redirectUrl - ' . $redirectUrl);

        return redirect($redirectUrl);
    }
}
