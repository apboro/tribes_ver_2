<?php

namespace App\Http\Controllers\APIv3;

use App\Events\SubscriptionMade;
use App\Helper\PseudoCrypt;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmails;
use App\Models\Payment;
use App\Repositories\Payment\PaymentRepository;
use App\Services\SMTP\Mailer;
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use App\Services\Tinkoff\TinkoffService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

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

    /**
     * TODO Tests
     *
     * @param Request $request
     * @param $hash
     * @param $telegramId
     * @return \App\Http\ApiResponses\ApiResponseCommon|\App\Http\ApiResponses\ApiResponseError
     */
    public function successPayment(Request $request, $hash, $telegramId = NULL)
    {
        $payment = Payment::find(PseudoCrypt::unhash($hash));
        if ($payment->status === 'CONFIRMED') {
            Event::dispatch(new SubscriptionMade($payment->payer, $payment->payable));
        }

        return ApiResponse::common(['success_redirect_url' => env('FRONTEND_URL').'/subscriptions?payment_result=success']);
    }

}