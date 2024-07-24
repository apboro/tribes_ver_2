<?php

namespace App\Services\CryptoWallet;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Services\Pay\PayReceiveService;
use Illuminate\Support\Facades\Log;

class Notify
{
    const STATUS_SUCCESS = 'PAID';

    public static function handle(array $data): bool
    {
        if (!self::verifyWebhook()) {
            Log::error('Error webhook verification', 
                [
                    'headers' => request()->headers->all(),
                    'body' => request()->getContent(),
                    'url' => request()->fullUrl(),
                    'method' => request()->method(),
                ]);
            return false;            
        }
        
        $payment = Payment::find($data['order_id']);
        if (!$payment) {
            Log::error('Error crypto payment - no payment', ['data' => $data]);
            return false;
        }

        if ($data['status'] == self::STATUS_SUCCESS &&
        $payment->status != PaymentStatus::CONFIRMED && 
        $data['customer_telegram_user_id'] == $payment->telegram_user_id) {
            $payment->setAsConfirmed();
            PayReceiveService::actionAfterPayment($payment);
        }

        return true;
    }

    private static function verifyWebhook(): bool
    {
        $timestamp = request()->header('X-Api-Timestamp');
        $signature = request()->header('X-Api-Signature');
        $body = request()->getContent();

        $path = request()->getPathInfo();
        $stringToEncrypt = $path . '.' . $timestamp . '.' . base64_encode($body);

        $accessToken = config('crypto_wallet.token');

        $hash = base64_encode(hash_hmac('sha256', $stringToEncrypt, $accessToken, true));

        return $hash === $signature;
    }
}