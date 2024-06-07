<?php

namespace App\Services\Yookassa;

use App\Helper\PseudoCrypt;
use App\Models\Payment as P;
use App\Models\Shop;
use App\Models\User;
use App\Services\Pay\PaySystemAcquiring;
use App\Services\Yookassa\CashRegister;
use Illuminate\Support\Facades\Log;
use YooKassa\Client;

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

    private function getAuthInfo(): array
    {
        return [config('yookassa.client_id'), config('yookassa.secret_key')];
    }

    private function getClientAuthToken(): string
    {
        return $this->payment->payable->shop->yookassaKey->oauth;
    }

    public function run(): P
    {
        $client = new Client();
        if ($this->payment->type == 'shopOrder') {
            $client->setAuthToken($this->getClientAuthToken());
        } else {
            $client->setAuth(...$this->getAuthInfo());
        }
        
        $paymentData = $this->getParams();
        try {
            $response = $client->createPayment($paymentData, $this->payment->id);
            $confirmationUrl = $response->getConfirmation()->getConfirmationUrl();
            $this->payment->updateRecord([
                'OrderId' => $this->orderId,
                'paymentId' => $response->getId() ?? '',
                'paymentUrl' => $confirmationUrl,
                'status' => 'NEW',
                'error' => '',
            ]);
        } catch (\Exception $e) {
            Log::info('Error yoomoney payment', ['exception' => $e]);
            $this->payment->updateRecord([
                'error' => 'Ошибка при создании платежа.',
            ]);
        }

        return $this->payment;
    }

    private function getParams(): array
    {
        $params =  [
            'amount' => [
                'value' => $this->getAmount(),
                'currency' => 'RUB',
                ],
            'confirmation' => [
                'type' => 'redirect',
                'locale' => 'ru_RU',
                'return_url' => $this->getResultUrl(), // getBackUrl() FAIL URL
            ],
            'capture' => true,
            'description' => $this->serviceName,
            'metadata' => [
                'orderNumber' => $this->payment->id
            ],
            'receipt' => [
                'customer' => [
                    'full_name' => $this->payer->name,
                    'email' => $this->payer->email,
                    'phone' => $this->payer->phone
                ],
                'items' => $this->getCashCheck()
            ]
        ];
        Log::info('Params for sending to yookassa', ['params' => $params]);

        return $params;
    }

    private function getCashCheck(): array
    {
        return CashRegister::buildCashCheck($this->type, $this->payer, $this->payFor);
    }

    private function getAmount(): float
    {
        return (int)$this->amount / 100;
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

    public static function isWorkWithShop(Shop $shop): bool 
    {
        return (bool)($shop->yookassaKey->oauth ?? false);
    }
}