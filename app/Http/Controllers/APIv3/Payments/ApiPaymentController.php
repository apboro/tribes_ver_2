<?php

namespace App\Http\Controllers\APIv3\Payments;

use App\Helper\PseudoCrypt;
use App\Http\Controllers\Controller;
use App\Models\Market\ShopOrder;
use App\Models\Payment;
use App\Models\Publication;
use App\Models\Webinar;
use App\Models\User;
use App\Repositories\Payment\PaymentRepository;
use App\Services\Pay\PayService;
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
            Log::debug('successPayment $redirectUrl - ' . $redirectUrl);
            return redirect($redirectUrl);
        }

        $part = '';
        if ($payment->type === 'subscription') {
            $part = '/app/subscriptions?payment_result=success';
        } elseif ($payment->type === 'donate') {
            $part = '/app/public/donate/thanks';
        } elseif ($payment->type === 'tariff') {
            $part = '/app/public/tariff/' . $payment->community->tariff->inline_link . '/thanks?' . http_build_query([
                'paymentId' => PseudoCrypt::hash($payment->id)
            ]);
        } elseif ($payment->type === 'publication') {
            $publication = Publication::find($payment->payable_id);
            $part = '/courses/member/post/' . $publication->uuid;
        } elseif ($payment->type === 'webinar') {
            $webinar = Webinar::find($payment->payable_id);
            $part = '/courses/member/webinar-preview/' . $webinar->uuid;
        }  elseif ($payment->type === PayService::SHOP_ORDER_TYPE_NAME) {
            $isUri = str_contains($request->success_url, '/');
            if (!$isUri) {
                $redirectUrl = $this->getMarketUrl() . $request->success_url;
                $part = false;
            } else {
                $redirectUrl = config('app.frontend_url') . $request->success_url;
            }
        }

        if ($part) {
            $redirectUrl = $request->success_url ?? config('app.frontend_url') . $part;
        }

        Log::debug('successPayment $redirectUrl - ' . $redirectUrl);

        return redirect($redirectUrl);
    }

    /**
     * @return string
     */
    public function getMarketUrl(): string
    {
        return 'https://t.me/' .
            config('telegram_bot.bot.botName') . '/' .
            config('telegram_bot.bot.botName') . '?startapp=' . 'success-';
    }
}
