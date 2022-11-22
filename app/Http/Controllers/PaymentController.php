<?php

namespace App\Http\Controllers;

use App\Exceptions\PaymentException;
use App\Filters\PaymentFilter;
use App\Helper\PseudoCrypt;
use App\Mail\ExceptionMail;
use App\Models\Accumulation;
use App\Models\Community;
use App\Models\Payment;
use App\Models\TestData;
use App\Repositories\Community\CommunityRepositoryContract;
use App\Repositories\Donate\DonateRepositoryContract;

use App\Repositories\Payment\PaymentRepository;
use App\Services\SMTP\Mailer;
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use App\Services\Tinkoff\TinkoffService;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;


class PaymentController extends Controller
{
    private $paymentRepo;
    protected TelegramMainBotService $botService;
    private TelegramLogService $telegramLogService;

    public function __construct(
        PaymentRepository      $paymentRepo,
        TelegramMainBotService $botService,
        TelegramLogService     $telegramLogService
    )
    {
        $this->botService = $botService;
        $this->paymentRepo = $paymentRepo;
        $this->telegramLogService = $telegramLogService;
    }

    public function list()
    {
        return redirect()->route('payment.card.list');
    }

    public function cardList()
    {
        return view('common.cash.card.list');
    }

    public function cardAdd()
    {
        return view('common.cash.card.form');
    }

    public function incomeList(PaymentFilter $filters)
    {
        $payments = $this->paymentRepo->getList($filters);
        return view('common.cash.income.list')->withPayments($payments);
    }

    public function outcomeList()
    {
        $accumulations = Accumulation::owned()->orderBy('created_at', 'DESC')->get();
//        $payments = $this->paymentRepo->getList();
        return view('common.cash.outcome.list')
            ->withAccumulations($accumulations);
    }

    public function successPage(Request $request, $hash, $telegramId = NULL)
    {
        $payment = Payment::find(PseudoCrypt::unhash($hash));

        if($payment->isTariff()) {
            $v = view('mail.telegram_invitation')->withPayment($payment)->render();
            new Mailer('Сервис Spodial', $v, 'Приглашение', $payment->payer->email);
            return view('common.tariff.success')->withPayment($payment);
        }
        return view('common.donate.success')->withPayment($payment);
    }

    public function notify(Request $request)
    {
        $data = $request->all();
//        if (empty($data)) {
//            return response('OK', 200);
//        }

        if ($data['Status'] == 'REFUNDED') {
            TelegramLogService::staticSendLogMessage("Попытка сделать возврат " . json_decode($data));
            return response('OK', 200);
        }
        if ($data['Status'] == 'AUTHORIZED') {
            return response('OK', 200);
        }

        if (env('GRAB_TEST_DATA') === true) {
            Storage::disk('tinkoff_data')->put("notify_payment_{$data['OrderId']}_{$data['Status']}.json", json_encode($data, JSON_PRETTY_PRINT));
        }

        if ($this->accessor($request)) {
            $payment = Payment::where('OrderId', $request['OrderId'])->where('paymentId', $request['PaymentId'])->first();

            if (!$payment) {
                (new PaymentException("NOTY: Платёж с OrderId " . $request['OrderId'] . " и PaymentId " .
                    $request['PaymentId'] . " не найден"))->report();
                TelegramLogService::staticSendLogMessage("Tinkoff получил ответ ОК");
                return response('OK', 200);
            }

            $previous_status = $payment->status;

            $payment->status = $request->Status;
            $payment->SpAccumulationId = $request->SpAccumulationId ?? null;
            $payment->RebillId = $request->RebillId ?? null;
            $payment->save();

            if(TinkoffService::checkStatus($request, $payment, $previous_status)){
                TelegramLogService::staticSendLogMessage("Tinkoff получил ответ ОК");
                return response('OK', 200);
            }

        } else {
            //todo сделать через Исключение
            (new PaymentException("Банк обратился c уведомлением, но не прошел проверку " . json_encode($data)))->report();

        }
//        return response('OK', 200);
    }

    private function accessor($request)
    {
        if (
            $request->TerminalKey == null
        ) {
            $this->telegramLogService->sendLogMessage(json_encode($request->all()));
            return false;
        } else {
            return true;
        }
    }

}
