<?php

namespace App\Http\Controllers\APIv3\Payments;

use App\Events\BuyPublicaionEvent;
use App\Events\BuyWebinarEvent;
use App\Events\SubscriptionMade;
use App\Events\TariffPayedEvent;
use App\Helper\PseudoCrypt;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmails;
use App\Models\Payment;
use App\Models\Publication;
use App\Models\Webinar;
use App\Models\User;
use App\Repositories\Payment\PaymentRepository;
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use App\Services\Tinkoff\TinkoffService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class ApiPaymentController extends Controller
{
    private $paymentRepo;
    protected TelegramMainBotService $botService;
    private TelegramLogService $telegramLogService;
    private TinkoffService $tinkoff;

    const WEBINAR_BUY_EXPIRATION = 365;

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
    public function successPayment(Request $request, $hash)
    {
        $payment = Payment::find(PseudoCrypt::unhash($hash));
        $redirectUrl = '';
        if ($payment->status === 'CONFIRMED' && $payment->type === 'subscription') {
            Event::dispatch(new SubscriptionMade($payment->payer, $payment->payable));
            $redirectUrl = $request->success_url ?? config('app.frontend_url') . '/app/subscriptions?payment_result=success';
        }

        if ($payment->status === 'CONFIRMED' && $payment->type === 'donate') {
            $redirectUrl = $request->success_url ?? config('app.frontend_url') . '/app/public/donate/thanks';
        }

        if ($payment->status === 'CONFIRMED' && $payment->type === 'tariff') {

            $tariff = $payment->community->tariff;

            $redirectUrl = $request->success_url ?? config('app.frontend_url') . '/app/public/tariff/' . $tariff->inline_link . '/thanks?' . http_build_query([
                'paymentId' => PseudoCrypt::hash($payment->id)
            ]);
            Event::dispatch(new TariffPayedEvent($payment->payer, $payment));
        }

        if ($payment->status === 'CONFIRMED' && $payment->type === 'publication') {

            $user = $payment->payer;
            $publication = Publication::find($payment->payable_id);
            $user->publications()->attach($publication->id, [
                'cost' => $publication->price === null ? 0 : $publication->price,
                'byed_at' => Carbon::now(),
                'expired_at' => Carbon::now()->addDays(365),
            ]);

            Event::dispatch(new BuyPublicaionEvent($publication, $user));
            $redirectUrl = $request->success_url ?? config('app.frontend_url') . '/courses/member/post/' . $publication->uuid;
        }

        if ($payment->status === 'CONFIRMED' && $payment->type === 'webinar') {

            $user = $payment->payer;
            $webinar = Webinar::find($payment->payable_id);
            $user->webinars()->attach($webinar->id, [
                'cost' => $webinar->price === null ? 0 : $webinar->price,
                'byed_at' => Carbon::now(),
                'expired_at' => Carbon::now()->addDays(self::WEBINAR_BUY_EXPIRATION),
            ]);

            Event::dispatch(new BuyWebinarEvent($webinar, $user));
            $redirectUrl = $request->success_url ?? config('app.frontend_url') . '/courses/member/webinar/' . $webinar->uuid;
        }

        Log::debug('successPayment $redirectUrl - ' . $redirectUrl);

        return redirect($redirectUrl);
    }
}