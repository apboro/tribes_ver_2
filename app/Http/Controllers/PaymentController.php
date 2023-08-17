<?php

namespace App\Http\Controllers;

use App\Exceptions\PaymentException;
use App\Filters\PaymentFilter;
use App\Helper\PseudoCrypt;
use App\Jobs\SendEmails;
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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;


class PaymentController extends Controller
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

/** Reworked for APIv3
     public function successPage(Request $request, $hash, $telegramId = NULL)
    {
        $payment = Payment::find(PseudoCrypt::unhash($hash));

        if (!$payment->comment == 'trial') {
            $state = json_decode($this->tinkoff->payTerminal->getState(['PaymentId' => $payment->paymentId]), true);
            while ($state['Status'] != 'AUTHORIZED') {
                $state = json_decode($this->tinkoff->payTerminal->getState(['PaymentId' => $payment->paymentId]), true);
                sleep(1);
                if ($state['Status'] == 'REJECTED') return redirect()->back(404)->withErrors('Оплата была отклонена');
            }
        }

            if ($payment->isTariff()) {
                if ($payment->comment !== 'trial') {
                    $v = view('mail.telegram_invitation')->withPayment($payment)->render();
                } else {
                    $variant = $payment->community->tariff->variants()->find($payment->payable_id);
                    $v = view('mail.telegram_invitation_trial')->withPayment($payment)->withVariant($variant)->render();
                }
                SendEmails::dispatch($payment->payer->email, 'Приглашение', 'Сервис Spodial', $v);
                return view('common.tariff.success')->withPayment($payment);
            }
            return view('common.donate.success')->withPayment($payment);
        }
*/

    public function notify(Request $request)
    {
        $data = $request->all();
        TelegramLogService::staticSendLogMessage("Notify from Tinkoff: " . json_encode($data));
//        if (empty($data)) {
//            return response('OK', 200);
//        }

        if ($data['Status'] == 'REFUNDED') {
            Log::log('Tinkoff notify Request status Status Попытка вывода средств ' . json_encode($data));
            TelegramLogService::staticSendLogMessage("Попытка вывода средств " . json_encode($data));
            return response('OK', 200);
        }

        if (true) {
            Storage::disk('tinkoff_data')->put("notify_payment_{$data['OrderId']}_{$data['Status']}.json", json_encode($data, JSON_PRETTY_PRINT));
        }

        if ($this->accessor($request)) {
            Log::log('Tinkoff intered to accesor' . json_encode($data));
            $payment = Payment::where('OrderId', $request['OrderId'])->where('paymentId', $request['PaymentId'])->first();

            if (!$payment) {
                Log::log('Tinkoff paymend not found r' .   json_encode($request['OrderId'], JSON_UNESCAPED_UNICODE));
                (new PaymentException("NOTY: Платёж с OrderId " . $request['OrderId'] . " и PaymentId " .
                    $request['PaymentId'] . " не найден"))->report();
                return response('OK', 200);
            }

            $previous_status = $payment->status;

            $payment->status = $request->Status;
            $payment->SpAccumulationId = $request->SpAccumulationId ?? null;
            $payment->RebillId = $request->RebillId ?? null;
            $payment->save();

            if(TinkoffService::checkStatus($request, $payment, $previous_status)){
//                TelegramLogService::staticSendLogMessage("Notify from tinkoff: ". json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                return response('OK', 200);
            }

        } else {
            //todo сделать через Исключение
            Log::log('Tinkoff notifyБанк обратился c уведомлением, но не прошел проверку ' . json_encode($data));
            (new PaymentException("Банк обратился c уведомлением, но не прошел проверку " . json_encode($data)))->report();

        }
//        return response('OK', 200);
    }

    private function accessor($request)
    {
        if (
            $request->TerminalKey == null
        ) {
            log::info('accessor TerminalKey is null');
            $this->telegramLogService->sendLogMessage(json_encode($request->all()));
            return false;
        } else {
            log::info('accessor TerminalKey ok');
            return true;
        }
    }

}
