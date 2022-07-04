<?php

namespace App\Http\Controllers;

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
        PaymentRepository $paymentRepo,
        TelegramMainBotService $botService,
        TelegramLogService $telegramLogService
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
        
//        $payment = $this->paymentRepo->freshStatus($payment, $telegramId); //DEPRECATED
        
        //if($payment && $payment->)

        return view('common.donate.success')->withPayment($payment);
    }

    public function notify(Request $request)
    {
        if($request->all() == []){
            return response('OK', 200);
        }
        Storage::prepend('Tinkoff_notify.log', json_encode('Tinkoff noty ' . Carbon::now()->format('H:i:s')));
        Storage::prepend('Tinkoff_notify.log', json_encode($request->all()));

        if($this->accessor($request)){
            $payment = Payment::where('OrderId', $request['OrderId'])->where('paymentId', $request['PaymentId'])->first();

            if(!$payment){
                $this->telegramLogService->sendLogMessage(
                    "NOTY: Платёж с OrderId " . $request['OrderId'] . " и PaymentId " .
                    $request['PaymentId'] . " не найден");
                return response('OK', 200);
            }

            $previous_status = $payment->status;

            $payment->status = $request->Status;
            $payment->SpAccumulationId = $request->SpAccumulationId ?? null;
            $payment->RebillId = $request->RebillId ?? null;
            $payment->save();

            TinkoffService::checkStatus($request, $payment, $previous_status);

        } else {
            $this->botService->sendMessageFromBot(config('telegram_bot.bot.botName'),
                env('TELEGRAM_LOG_CHAT'), "Банк обратился c уведомлением, но не прошел проверку " . json_encode($request->all()), false, []);
        }
        return response('OK', 200);
    }
    private function accessor($request)
    {
        if(
            $request->TerminalKey == null
        ){
            $this->telegramLogService->sendLogMessage(json_encode($request->all()));
            return false;
        } else {
            return true;
        }
    }

}
