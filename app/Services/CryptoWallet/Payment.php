<?php

namespace App\Services\CryptoWallet;

use App\Helper\PseudoCrypt;
use App\Models\Payment as P;
use App\Models\Shop;
use App\Models\User;
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
        try {
        $response = Http::asForm()->withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
            'X-Access-Token' => config('crypto_wallet.token'),
            ])->post(config('crypto_wallet.server'), $this->getParams());  

            $responseData = $response->json();
            if ($response->status() != 200) {
                Log::info('Error crypto payment', ['responseData' => $responseData]);
                $this->payment->updateRecord([
                    'error' => 'Payment system code error',
                ]);
            }

            $this->payment->updateRecord([
                'OrderId' => $this->orderId,
                'paymentId' => $responseData['uuid'] ?? '',
                'paymentUrl' => $responseData['direct_payment_link'] ?? '',
                'status' => 'NEW',
                'error' => '',
            ]);            

        } catch (\Exception $e) {
            Log::info('Error payment', ['exception' => $e]);
            $this->payment->updateRecord([
                'error' => 'Error payment',
            ]);
        }

        return $this->payment;
    }

    private function getParams(): array
    {
        $params =  [
            "currency" => 'USDT',
            "order_id" => $this->payment->id,
            "amount" => $this->getAmount(),
            "customer_telegram_user_id" => $this->payment->telegram_user_id,
            "return_url" => $this->getResultUrl(),
            "fail_return_url" => $this->getBackUrl(),
            "description" => $this->getDescription(),
            "lifetime" => 900,
            ];

        Log::info('Params for sending to yookassa', ['params' => $params]);

        return $params;
    }

    private function getAmount(): float
    {
        return $this->amount / 100;
    }

    private function getDescription(): string
    {
        return 'Purchase of goods';
    }

    private function getBackUrl(): string
    {
        // $link = 'https://t.me/' . config('telegram_bot.bot.botName') . '/' . config('telegram_bot.bot.marketName') . '?startapp=' . $this->payment->payable->shop_id . '&startApp=' . $this->payment->payable->shop_id;
        $link = 'https://t.me/' . config('telegram_bot.bot.botName') . '/' . config('telegram_bot.bot.marketName') . '?startapp';

        return $link;
    }

    private function getResultUrl(): string
    {
        // $link = 'https://t.me/' . config('telegram_bot.bot.botName') . '/' . config('telegram_bot.bot.marketName') .  '?startapp=status=' . $this->payment->payable_id . '&startApp=status=' . $this->payment->payable_id;
        $link = 'https://t.me/' . config('telegram_bot.bot.botName') . '/' . config('telegram_bot.bot.marketName') . '?startapp';

        return $link;
    }

    public static function isWorkWithShop(Shop $shop): bool 
    {
        return (bool)($shop->yookassaKey->oauth ?? false);
    }
}