<?php

namespace App\Services\Robokassa;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use \App\Models\Payment;
use App\Services\Pay\PayReceiveService;
use App\Services\Robokassa\Payment as RobokassaPayment;

class Notify
{
    public static function handle(array $data): bool
    {
        if (!$payment = Payment::find($data['InvId'])) {
            return false;
        }

        $signature = RobokassaPayment::calculateSignature([
            $payment->price_in_rubles,
            $payment->id,
            $payment->type === PaymentType::SHOP_ORDER
                ? $payment->payable->shop->robokassaKey->second_password
                : config('robokassa.default_second_password')
        ]);

        $isValidSignature = $signature === $data['SignatureValue'];

        if ($payment->status != PaymentStatus::CONFIRMED && $isValidSignature) {
            $payment->setAsConfirmed();
            PayReceiveService::actionAfterPayment($payment);

            return true;
        }

        return false;
    }
}