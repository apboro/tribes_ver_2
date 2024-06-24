<?php

namespace App\Services\Robokassa;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Models\Payment as P;
use App\Models\Shop;
use App\Services\Pay\PaySystemAcquiring;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Payment extends PaySystemAcquiring
{
    public P $payment;

    public function setPayment(P $payment): self
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

        return $this;
    }

    public function run(): P
    {
        $url = config('robokassa.payment_creation_url');

        $paymentData = $this->getParams();

        $client = new Client();
        $response = $client->post($url, ['form_params' => $paymentData]);
        $responseData = json_decode($response->getBody()->getContents(), true);
        $paymentUrl = config('robokassa.payment_redirect_url') . $responseData['invoiceID'];

        if ($responseData['errorCode'] === 0) {
            $this->payment->updateRecord([
                'OrderId' => $this->orderId,
                'paymentId' => $responseData['invoiceID'],
                'paymentUrl' => $paymentUrl,
                'status' => PaymentStatus::NEW,
                'error' => '',
            ]);
        } else {
            $this->payment->updateRecord([
                'error' => $response['errorMessage'],
            ]);
        }

        return $this->payment;
    }

    private function getParams(): array
    {
        $credentials = $this->getCredentials();

        $parameters = [
            'MerchantLogin' => $credentials['MerchantLogin'],
            'OutSum' => (string) $this->payment->price_in_rubles,
            'Description' => $this->serviceName,
            'InvId' => $this->payment->id,
            'Culture' => 'ru',
            'EMail' => $this->payer->email,
            'UserIp' => request()->ip(),
            'IsTest' => (int) $this->isTest()
        ];

        $parameters['SignatureValue'] = $this->calculateSignature([
            $parameters['MerchantLogin'],
            $parameters['OutSum'],
            $parameters['InvId'],
            $parameters['UserIp'],
            $credentials['FirstPassword']
        ]);

        return $parameters;
    }

    public static function calculateSignature(array $data): string
    {
        if (empty($data)) {
            throw new \Exception('Error creating signature: Data is empty');
        }

        $signature = implode(':', $data);

        return Str::upper(md5($signature));
    }

    private function getCredentials(): array
    {
        if ($this->isShopOrder()) {
            $credentials = [
                'MerchantLogin' => $this->payment->payable->shop->robokassaKey->merchant_login,
                'FirstPassword' => $this->payment->payable->shop->robokassaKey->first_password,
            ];
        } else {
            $credentials = [
                'MerchantLogin' => config('robokassa.default_merchant_login'),
                'FirstPassword' => config('robokassa.default_first_password'),
            ];
        }

        if (empty($credentials['MerchantLogin']) || empty($credentials['FirstPassword'])) {
            Log::alert('Attempt to make a payment with client keys, but there are no keys.', ['object' => $this]);
            throw new \Exception('Нет ключей платежной системы.');
        }

        return $credentials;
    }

    private function isShopOrder(): bool
    {
        return $this->payment->type === PaymentType::SHOP_ORDER;
    }

    public static function isTest(): bool
    {
        return config('robokassa.is_test') === true;
    }

    public static function isWorkWithShop(Shop $shop): bool
    {
        $keys = $shop->robokassaKey;

        return $keys && $keys->merchant_login && $keys->first_password && $keys->second_password;
    }

    public function testKeys(string $login, string $firstPassword): bool
    {
        $url = config('robokassa.payment_creation_url');
        $parameters = $this->getTestParams($login, $firstPassword);

        $client = new Client();
        $response = $client->post($url, ['form_params' => $parameters]);
        $responseData = json_decode($response->getBody()->getContents(), true);

        if ($responseData['errorCode'] !== 0) {
            return false;
        }

        return true;
    }

    private function getTestParams(string $login, string $firstPassword): array
    {
        $parameters = [
            'MerchantLogin' => $login,
            'OutSum' => '1',
            'Description' => 'Проверка ключей',
            'Culture' => 'ru',
            'IsTest' => (int) $this->isTest()
        ];

        $parameters['SignatureValue'] = $this->calculateSignature([
            $parameters['MerchantLogin'],
            $parameters['OutSum'],
            '',
            $firstPassword
        ]);

        return $parameters;
    }
}