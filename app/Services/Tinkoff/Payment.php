<?php

namespace App\Services\Tinkoff;

use App\Helper\PseudoCrypt;
use App\Models\Payment as P;
use App\Services\TelegramLogService;
use App\Services\Tinkoff\TinkoffApi;
use App\Services\Pay\PayReceiveService;
use Illuminate\Support\Facades\Log;

class Payment
{
    private TinkoffService $tinkoff;

    public $amount = 0; // Сумма в копейках
    public $payer;
    public P $payment;
    public $accumulation;
    public $type;
    public $charged;
    public $payFor;
    public $orderId;
    private $rebillId;
    private $serviceName;
    private $email;
    private $phone;
    private $quantity;

    private $callbackUrl;
    public bool $recurrent = false;
    public $telegram_id;
    private $successUrl;

    public function __construct()
    {
        $this->tinkoff = new TinkoffService();
        $this->callbackUrl = route('tinkoff.notify');
    }

    public static function create(): Payment
    {
        return new self();
    }

    public function setAmount(?int $amount): Payment
    {
        $this->amount = $amount;

        return $this;
    }

    public function setSuccessUrl(?string $url): Payment
    {
        $this->successUrl = $url;

        return $this;
    }

    public function setRecurrent(bool $state = false): Payment
    {
        $this->recurrent = (bool)$state;

        return $this;
    }

    public function setType(?string $type): Payment
    {
        $this->type = $type;

        return $this;
    }

    public function payFor($payFor): Payment
    {
        $this->payFor = $payFor;

        return $this;
    }

    public function setCharged(bool $charged = false): Payment
    {
        $this->charged = $charged;

        return $this;
    }

    public function setAccumulation($accumulation): Payment
    {
        $this->accumulation = $accumulation;

        return $this;
    }

    public function setTelegramId(?int $telegramId): Payment
    {
        $this->telegram_id = $telegramId;

        return $this;
    }

    public function setPayer($payer): Payment
    {
        $this->payer = $payer;

        return $this;
    }

    public function setRebillId(?int $rebillId): Payment
    {
        $this->rebillId = $rebillId;

        return $this;
    }

    public function setPayment($payment): Payment
    {
        $this->payment = $payment;
        if ($payment->telegram_user_id) {
            $this->setTelegramId($payment->telegram_user_id);
        }
        if ($payment->amount) {
            $this->setAmount($payment->amount);
        }
        if ($payment->type) {
            $this->setType($payment->type);
        }
        if ($payment->RebillId) {
            $this->setRebillId($payment->rebillId);
        }
        if ($payment->SpAccumulationId) {
            $this->setAccumulation($payment->SpAccumulationId);
        }

        return $this;
    }

    public function setOrderId(?string $orderId): Payment
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function setServiceName(?string $serviceName): Payment
    {
        $this->serviceName = $serviceName;

        return $this;
    }

    public function setEmail(?string $email): Payment
    {
        $this->email = $email;

        return $this;
    }

    public function setPhone(?string $phone): Payment
    {
        $this->phone = $phone;

        return $this;
    }

    public function setQuantity(?int $quantity): Payment
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function pay()
    {
        log::info('Начало оплаты Тинькофф');

        $params = $this->params(); // Генерируем параметры для оплаты исходя из входных параметров

        if ($this->type == 'subscription') {
            $resp = json_decode($this->tinkoff->initDirectPay($params)); // Шлём запрос в банк на терминал direct
        } else {
            $resp = json_decode($this->tinkoff->initPay($params)); // Шлём запрос в банк на терминал pay
        }
        Log::debug('Запрос INIT завершен, см. контекст.', [$resp]);

        if (isset($resp->Success) && $resp->Success) {
            $this->payment->updateRecord([
                'OrderId' => $this->orderId,
                'paymentId' => $resp->PaymentId,
                'paymentUrl' => $resp->PaymentURL,
                'response' => 'deprecated',
                'status' => $resp->Status,
                'token' => hash('sha256', $this->payment->id),
                'error' => $resp->ErrorCode
            ]);
            //    if ($this->payFor) {
            //        $this->payFor->payments()->save($this->payment);
            //    }
            //    $this->payment->payer()->associate($this->payer)->save();

            if ($this->charged) {
                $charge = $this->payCharge();
                if (!$charge) {
                    return false;
                }
            }

            return $this->payment;
        } else {
            $this->sendLogs("Оплата по карте с ошибкой: " . json_encode($resp, JSON_UNESCAPED_UNICODE));

            return false;
        }
    }

    private function payCharge(): bool
    {
        $chargeRes = $this->tinkoff->payTerminal->Charge([
            'PaymentId' => $this->payment->paymentId,
            'RebillId' => !empty($this->rebillId) ? $this->rebillId : null,
        ]);

        $chargeRes = json_decode($chargeRes);
        log::debug('Автосписание, см. контекст', [$chargeRes]);

        if (isset($chargeRes->Success) && $chargeRes->Success) {
            log::debug('Автосписание прошло успешно');
            $previous_status = $this->payment->status;
            $this->payment->updateRecord([
                'status' => $chargeRes->Status,
                'SpAccumulationId' => $chargeRes->SpAccumulationId ?? null,
                'RebillId' => $chargeRes->RebillId ?? null,
            ]);

            PayReceiveService::run($chargeRes, $this->payment, $previous_status);
        } else {
            $this->sendLogs("Charge ответил с ошибкой: " . json_encode($chargeRes, JSON_UNESCAPED_UNICODE));

            return false;
        }

        //    if ($this->payFor) {
        //        $this->payFor->payments()->save($this->payment);
        //    }
        //    $this->payment->payer()->associate($this->payer)->save();

        return true;
    }

    private function params(): array
    {
        $attaches = [];

        if ($this->payment) {
            $attaches['hash'] = PseudoCrypt::hash($this->payment->id);
        }
        if ($this->telegram_id) {
            $attaches['telegram_id'] = $this->telegram_id;
        }
        if ($this->successUrl) {
            $attaches['success_url'] = $this->successUrl;
        }

        $receiptItem = [[
            'Name' => $this->serviceName,
            'Price' => $this->amount / 100,
            'Quantity' => $this->quantity,
            'Amount' => $this->amount,
            'PaymentMethod' => TinkoffApi::$paymentMethod['full_prepayment'],
            'PaymentObject' => TinkoffApi::$paymentObject['service'],
            'Tax' => TinkoffApi::$vats['none']
        ]];

        $receipt = [
            'EmailCompany' => $this->email,
            'Phone' => $this->phone,
            'Taxation' => TinkoffApi::$taxations['osn'],
            'Items' => TinkoffApi::balanceAmount(false, $receiptItem, $this->amount),
        ];

        $params = [
            'NotificationURL' => $this->callbackUrl,
            'OrderId' => $this->orderId,
            'Amount' => $this->amount,
            'FailURL' => env('FRONTEND_URL') . '/app/subscriptions?payment_result=fail',
            'SuccessURL' => route('payment.success', $attaches),
            'DATA' => [
                'Email' => $this->payer ? $this->payer->email : '',
            ],
        ];
        $params['Receipt'] = $receipt;

        if ($this->type !== 'subscription') {
            $params = array_merge_recursive($params, $this->checkAccumulation());
        }

        return array_merge_recursive($params, $this->checkRecurrent());
    }

    private function checkAccumulation(): array
    {
        $params = [];
        if ($this->accumulation !== null) {
            $params['DATA']['StartSpAccumulation'] = false;
            $params['DATA']['SpAccumulationId'] = $this->accumulation->SpAccumulationId;
        } else {
            $params['DATA']['StartSpAccumulation'] = true;
        }

        return $params;
    }

    private function checkRecurrent(): array
    {
        $params = [];
        if ($this->recurrent) {
            $params['Recurrent'] = 'Y';
            $params['CustomerKey'] = $this->payer->getCustomerKey();
        }
        return $params;
    }

    /**
     * @param string $message
     * @return void
     */
    public function sendLogs(string $message): void
    {
        log::error($message);
        TelegramLogService::staticSendLogMessage($message);
    }
}