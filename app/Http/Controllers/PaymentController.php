<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\UnitpayRequest;
use App\Exceptions\PaymentException;
use App\Filters\PaymentFilter;
use App\Models\Accumulation;
use App\Models\Payment;
use App\Repositories\Payment\PaymentRepository;
use App\Services\Pay\PayReceiveService;
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use App\Services\Tinkoff\TinkoffService;
use App\Services\Unitpay\Notify as UnitpayNotify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

        return view('common.cash.outcome.list')
            ->withAccumulations($accumulations);
    }

    public function unitpayNotify(UnitpayRequest $request)
    {
        Log::info('Нотификация Unitpay', ['request' => $request]);
        if (UnitpayNotify::handle($request->all())){
            $responce = ['result' => 
                            ['message' => 'Запрос успешно обработан']];
        } else {
            $responce = ['error' => 
                            ['message' => 'Ошибка']];
        }

        return response(\json_encode($responce), 200);
    }

    public function notify(Request $request)
    {
        $data = $request->all();
        TelegramLogService::staticSendLogMessage("Notify from bank: " . json_encode($data));
        if (empty($data) || !isset($data['Status']) || !isset($data['OrderId'])) {
            Log::critical('Пришло пустое уведомление от банка', ['data' => $data]);
            return response('OK', 200);
        }

        /*if ($data['Status'] == 'REFUNDED') {
            Log::log('Попытка вывода средств ' . json_encode($data));
            TelegramLogService::staticSendLogMessage("Попытка вывода средств " . json_encode($data));
            return response('OK', 200);
        }*/

        Storage::disk('tinkoff_data')->put("notify_payment_{$data['OrderId']}_{$data['Status']}.json", json_encode($data, JSON_PRETTY_PRINT));

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

            $isSuccess = PayReceiveService::run($request, $payment, $previousStatus);
            if ($isSuccess) {
                if ($payment->status == 'CONFIRMED') {
                    PayReceiveService::actionAfterPayment($payment);
                }

                if (($payment->type === 'tonbot') && ($payment->status === 'COMPLETED' || $payment->status === 'REFUNDED')) {
                    PayReceiveService::actionAfterPayment($payment);
                }

                return response('OK', 200);
            }
        } else {
            Log::log('Tinkoff notifyБанк обратился c уведомлением, но не прошел проверку ' . json_encode($data));
            (new PaymentException("Банк обратился c уведомлением, но не прошел проверку " . json_encode($data)))->report();
        }
    }

    private function accessor($request)
    {
        if ($request->TerminalKey == null) {
            log::info('accessor TerminalKey is null');
            $this->telegramLogService->sendLogMessage(json_encode($request->all()));
            return false;
        } else {
            log::info('accessor TerminalKey ok');
            return true;
        }
    }
}
