<?php


namespace App\Services\Tinkoff;


use App\Helper\PseudoCrypt;
use App\Models\Accumulation;
use App\Models\Community;
use App\Models\Course;
use App\Models\DonateVariant;
use App\Models\Payment as P;
use App\Models\TariffVariant;
use App\Models\User;
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use App\Services\Tinkoff\TinkoffApi;
use phpDocumentor\Reflection\Types\False_;

/**
 * todo переименовать в более уникальное название путается с eloquent моделью
 */
class Payment
{
    private TinkoffService $tinkoff;

    public $amount = 0; // Сумма в копейках
    public string $comment;
    public $community;
    public $author;
    public $payer;
    public P $payment;
    public $accumulation;
    public $type;
    public $charged;
    public $payFor;
    public $orderId;

    private $callbackUrl;
    public bool $recurrent = false;
    public $telegram_id;

    public function __construct()
    {
        $this->tinkoff = new TinkoffService();
        $this->callbackUrl = route('tinkoff.notify');

        $this->init();
    }

    public function init()
    {
//        return $this;
    }

    public function amount($amount) : Payment
    {
        $this->amount = $amount;

        return $this;
    }

    public function recurrent($state = false) : Payment
    {
        $this->recurrent = (bool)$state;

        return $this;
    }

    public function payFor($payFor)
    {
        switch ($payFor){
            case $payFor instanceof TariffVariant:
                $this->type = 'tariff';
                break;
            case $payFor instanceof DonateVariant:
                $this->type = 'donate';
                break;
            case $payFor instanceof Course:
                $this->type = 'course';
                break;
            default:
                TelegramLogService::staticSendLogMessage("Оплата на свободную сумму");
                return false;
        }
        $this->payFor = $payFor;

        $this->community();

        return $this;
    }

    public function charged($charged = false) : Payment
    {
        $this->charged = $charged;

        return $this;
    }

    public function community()
    {
        $relation = $this->type;

        $this->community = $this->payFor->$relation()->first()->community()->first() ?? null;
        $this->author();

        return $this;
    }

    public function author()
    {
        $this->author = $this->payFor->getAuthor();

        $this->accumulation();
    }

    public function telegram()
    {
        $tu = $this->payer->telegramMeta()->first();

        $this->telegram_id = $tu ? $tu->telegram_id : null;
    }


    public function accumulation()
    {
        $this->accumulation = $this->author->getActiveAccumulation();
    }

    public function payer($user) : Payment
    {
        if($user){
            $this->payer = $user;
            $this->telegram();
        }
        return $this;
    }

    public function pay()
    {
        if($this->charged){ // Запрос на рекуррентный платёж, без подтверждения покупателем

            if(isset($this->payFor) && isset($this->payer)){ // Если указано за что платить и кто плательщик

                $this->payment = new P();
                $rebildPayment = $this->payment->tariffs()
                    ->where('payable_id', $this->payFor->id)
                    ->where('user_id', $this->payer->id)
                    ->where('RebillId', '!=', 'null')->latest()->first();

                if($rebildPayment){ // Если платёж найден - находим Community и владельца
                    $this->community = $rebildPayment->community()->first();
                    $this->author = $this->community->owner()->first();
                } else {
                    TelegramLogService::staticSendLogMessage("Рекурент основной платёж не найден Тариф: " . $this->payFor->id . ", Плательщик: " . $this->payer->id);
                }

            } else {
                TelegramLogService::staticSendLogMessage("Рекурент без указания кому и за что" . json_encode([$this->payFor, $this->payer]));
            }
        }

        $this->payment = new P();
        $this->payment->type = $this->type;
        $this->payment->amount = $this->amount;
        $this->payment->from = $this->payer ? $this->payer->name : 'Анонимный пользователь';
        $this->payment->community_id = $this->community ? $this->community->id : null;
        $this->payment->author = $this->payFor->getAuthor()->id ?? null;
        $this->payment->add_balance = $this->amount / 100;
        $this->payment->save();
        $this->orderId = $this->payment->id . date("_md_s");

        $params = $this->params(); // Генерируем параметры для оплаты исходя из входных параметров
        if ($params['Amount'] == 0)
        {
            $this->comment = 'trial';
            $resp = (object)[
                'PaymentId' => rand(1000000000, 9999999999),
                'PaymentURL' => route('payment.success', ['hash' =>PseudoCrypt::hash($this->payment->id), 'telegram_id'=>$this->telegram_id]),
                'Status' => 'CONFIRMED',
                'ErrorCode' => null,
                'Success' => true,
            ];
        } else {
            $resp = json_decode($this->tinkoff->initPay($params)); // Шлём запрос в банк
        }

        if(isset($resp->Success) && $resp->Success){

//            if(isset($resp->SpAccumulationId, $this->payment)){
//                $this->accumulation($resp->SpAccumulationId);
//            }


            $this->payment->OrderId = $this->orderId;
            $this->payment->paymentId = $resp->PaymentId;
            $this->payment->paymentUrl = $resp->PaymentURL;
            $this->payment->response = 'deprecated';
            $this->payment->status = $resp->Status;
            $this->payment->token = hash('sha256', $this->payment->id);
            $this->payment->error = $resp->ErrorCode;
            $this->payment->isNotify = isset($this->notify);
            $this->payment->comment = $this->comment ?? null;
            $this->payment->save();

            if($this->payFor){
                $this->payFor->payments()->save($this->payment);
            }
            $this->payment->payer()->associate($this->payer)->save();

            if($this->charged){
                $chargeRes = $this->tinkoff->payTerminal->Charge([
                    'PaymentId' => $this->payment->paymentId,
                    'RebillId' => !empty($rebildPayment->RebillId) ? $rebildPayment->RebillId : null,
                ]);
                $chargeRes = json_decode($chargeRes);

                if(isset($chargeRes->Success) && $chargeRes->Success){

                    $previous_status = $this->payment->status;
                    $this->payment->status = $chargeRes->Status;
                    $this->payment->SpAccumulationId = $chargeRes->SpAccumulationId ?? null;
                    $this->payment->RebillId = $chargeRes->RebillId ?? null;
                    $this->payment->save();

                    TinkoffService::checkStatus($chargeRes, $this->payment, $previous_status);
                } else {
                    //todo сохранять в лог файл TelegramLogService::staticSendLogMessage заменить на
                    // \App\Exceptions\TelegramException::report() сделать похожий для платежей
                    TelegramLogService::staticSendLogMessage("Charge ответил с ошибкой: " . json_encode($chargeRes, JSON_UNESCAPED_UNICODE));
                    return false;
                }
            }
            $this->payFor->payments()->save($this->payment);
            $this->payment->payer()->associate($this->payer)->save();

            return $this->payment;
        } else {
            TelegramLogService::staticSendLogMessage("Оплата по карте с ошибкой: " . json_encode($resp, JSON_UNESCAPED_UNICODE));
            return false;
        }
    }

    public function params()
    {
        $attaches = [];

        if($this->payment) {
            $attaches['hash'] = PseudoCrypt::hash($this->payment->id);
        }
        if($this->telegram_id) {
            $attaches['telegram_id'] = $this->telegram_id;
        }

        $receiptItem = [[
            'Name'          => 'Оплата за использование системы',
            'Price'         => $this->amount / 100,
            'Quantity'      => 1,
            'Amount'        => $this->amount,
            'PaymentMethod' => TinkoffApi::$paymentMethod['full_prepayment'],
            'PaymentObject' => TinkoffApi::$paymentObject['service'],
            'Tax'           => TinkoffApi::$vats['none']
        ]];

        $receipt = [
            'EmailCompany' => 'CoderYooda@gmail.com',
            'Phone'        => '89524365064', //Auth::user()->phone,
            'Taxation'     => TinkoffApi::$taxations['osn'],
            'Items'        => TinkoffApi::balanceAmount(false, $receiptItem, $this->amount),
        ];

        $params = [
            'NotificationURL' => $this->callbackUrl,
            'OrderId' => $this->orderId,
            'Amount'  => $this->amount,
            'SuccessURL' => $this->payFor instanceof Course ? $this->payFor->successPageLink() :route('payment.success', $attaches),
            'DATA'    => [
                'Email'  => $this->payer ? $this->payer->email : '',
            ],
        ];
        $params['Receipt'] = $receipt;

        $params = array_merge_recursive($params, $this->checkAccumulation());
        return array_merge_recursive($params, $this->checkRecurrent());
    }

    private function checkAccumulation()
    {
        $params = [];
        if($this->accumulation != null){
            $params['DATA']['StartSpAccumulation'] = false;
            $params['DATA']['SpAccumulationId'] = $this->accumulation->SpAccumulationId;
        } else {
            $params['DATA']['StartSpAccumulation'] = true;
        }
        return $params;
    }

    private function checkRecurrent()
    {
        $params = [];
        if($this->recurrent){
            $params['Recurrent'] = 'Y';
            $params['CustomerKey'] = $this->payer->getCustomerKey();
        }
        return $params;
    }

}