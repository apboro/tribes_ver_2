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
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use App\Services\Tinkoff\TinkoffService;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\Pay\PayReceiveService;

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


    public function notify(Request $request)
    {
        $data = $request->all();
        TelegramLogService::staticSendLogMessage("Notify from bank: " . json_encode($data));
        if (empty($data) || !isset($data['Status'])) {
            Log::critical('Пришло пустое уведомление от банка', ['data' => $data]);
            return response('OK', 200);
        }

        if ($data['Status'] == 'REFUNDED') {
            Log::log('Попытка вывода средств ' . json_encode($data));
            TelegramLogService::staticSendLogMessage("Попытка вывода средств " . json_encode($data));
            return response('OK', 200);
        }

        if (isset($data['OrderId']) && isset($data['Status'])) {
            Storage::disk('tinkoff_data')->put("notify_payment_{$data['OrderId']}_{$data['Status']}.json", json_encode($data, JSON_PRETTY_PRINT));
        }

        if ($this->accessor($request)) {
            $payment = Payment::where('OrderId', $request['OrderId'])->where('paymentId', $request['PaymentId'])->first();

            if (!$payment) {
                Log::info('Tinkoff paymend not found r' .   json_encode($request['OrderId'], JSON_UNESCAPED_UNICODE));
                (new PaymentException("NOTY: Платёж с OrderId " . $request['OrderId'] . " и PaymentId " .
                    $request['PaymentId'] . " не найден"))->report();
                return response('OK', 200);
            }

            $previousStatus = $payment->status;

            $payment->status = $request->Status;
            $payment->SpAccumulationId = $request->SpAccumulationId ?? null;
            $payment->RebillId = $request->RebillId ?? null;
            $payment->save();

            $isSuccess = PayReceiveService::paymentDbTransaction($request, $payment, $previousStatus);
            if($isSuccess){ // События при успешно проведенном платеже
                PayReceiveService::actionAfterPayment($payment, $data['Status']);

                return response('OK', 200);
            }

        } else {
            Log::log('Tinkoff notifyБанк обратился c уведомлением, но не прошел проверку ' . json_encode($data));
            (new PaymentException("Банк обратился c уведомлением, но не прошел проверку " . json_encode($data)))->report();

        }
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
