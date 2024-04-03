<?php

namespace App\Services\Unitpay;

use App\Helper\PseudoCrypt;
use App\Models\Payment as P;
use App\Models\User;
use App\Services\TelegramLogService;
use App\Services\Pay\PayReceiveService;
use App\Services\Pay\PaySystemAcquiring;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Payment extends PaySystemAcquiring
{
    public P $payment;
    protected User $seller;

    public function setPayment(P $payment): Payment
    {
        $this->payment = $payment;
        if ($payment->amount) {
            $this->setAmount($payment->amount);
        }
        if ($payment->type) {
            $this->setType($payment->type);
        }
        if ($payment->telegram_user_id) {
            $this->setTelegramId($payment->telegram_user_id);
        }
        $this->seller = User::findOrFail($this->payment->author);

        return $this;
    }

    public function run(): P
    {
        $response = Http::get('https://unitpay.ru/api', $this->getParams())->json();

        if (isset($response['result']) && $response['result']) {
            $this->payment->updateRecord([
                'OrderId' => $this->orderId,
                'paymentId' => $response['result']['paymentId'] ?? '',
                'paymentUrl' => $response['result']['redirectUrl'] ?? '',
                'status' => 'NEW',
                'error' => '',
            ]);
        }
        if (isset($response['error']) && $response['error']) {
            $this->payment->updateRecord([
                'error' => $response['error']['message'] ?? '',
            ]);
        }

        return $this->payment;
    }

    private function getFormSignature(array $params): string
    {
        $hashStr = $params['account'] . '{up}' . 
                    $params['currency'] . '{up}' . 
                    $params['desc'] . '{up}' . 
                    $params['sum'] . '{up}' . 
                    $params['secretKey'];

        return hash('sha256', $hashStr);
    }

    private function buildParams(): array
    {
        $unitpayAccessInfo = $this->getAccessInfo();

        $params = [
            'paymentType' => 'card',
            'sum' => $this->getAmount(),
            'desc' => $this->serviceName,
            'currency' => 'RUB',
            'backUrl' => $this->getBackUrl(),
            'account' => $this->getAccount(),
            'projectId' => $unitpayAccessInfo['projectId'], 
            'secretKey' => $unitpayAccessInfo['secretKey'],
            'resultUrl' => $this->getResultUrl(),
        ];

        $params['signature'] = $this->getFormSignature($params);
        $params = $params + $this->getCashCheck();

        if ($this->isTestPayment()) {
            $params['test'] = 1; // Для теста
            $params['login'] = $this->getLogin(); // Для теста - Ваш регистрационный email в системе UnitPay
        }
        Log::info('Params for sending to unitpay', ['params' => $params]);

        return $params;
    }

    private function getCashCheck(): array
    {
        return CashRegister::buildCashCheck($this->type, $this->payer, $this->payFor);
    }

    private function getParams(): array
    {
        $params = $this->buildParams();
        $requestParams = [];
        $requestParams['method'] = 'initPayment'; 
        foreach ($params as $key => $param) {
            $requestParams['params['.$key.']'] = $param;
        }

        return $requestParams;
    }

    private function getAmount(): float
    {
        return $this->amount / 100;
    }

    private function isPaymentForSeller(): bool
    {
        return $this->payment->type == 'shopOrder';
    }

    private function getAccessKeysForSeller(): array
    {
        $access = [];
        if ($this->payment->type == 'shopOrder') {
            $access = $this->seller->getUnitpayKeyByShopId($this->payFor->shop_id);
        }

        if (!empty($access['project_id']) && !empty($access['secretKey'])) {
            return [
                'projectId' => $access['project_id'],
                'secretKey' => $access['secretKey'],
            ];
        }

        Log::alert('Attempt to make a payment with client keys, but there are no keys.', ['object' => $this]);
        throw new \Exception('Нет ключей платежной системы.');
    }

    private function isTestPayment(): bool
    {
        return config('unitpay.test') === true;
    }

    private function getAccount(): string
    {
        return $this->payment->id;
    }

    private function getLogin(): string
    {
        return config('unitpay.account');
    }

    private function getAccessInfo(): array
    {
        if (($this->isTestPayment())) {
            return [
                'projectId' => config('unitpay.projectId'),
                'secretKey' => config('unitpay.secretKeyTest'),
            ];
        }

        if ($this->isPaymentForSeller()) {
            return $this->getAccessKeysForSeller();            
        }

        return [
            'projectId' => config('unitpay.projectId'),
            'secretKey' => config('unitpay.secretKey'),
        ];
    }

    private function getBackUrl(): string
    {
        return $this->failUrl;
    }

    private function getResultUrl(): string
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

        return route('payment.success', $attaches);
    }

}