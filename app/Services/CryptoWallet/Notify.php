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
}