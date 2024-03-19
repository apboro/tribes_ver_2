<?php

namespace App\Services\Unitpay;

use App\Helper\PseudoCrypt;
use App\Models\Payment;
use App\Services\Pay\PayReceiveService;
use Illuminate\Support\Facades\Log;

class Notify
{
    public static function handle(array $data): bool
    {
        $method = $data['method'] ?? '';
        $methodName = 'method' . ucfirst($method);

        if (method_exists(self::class, $methodName)) {
            return self::$methodName($data['params']);
        } 

        return false;
    }

    /**
     * @used
     */
    private static function methodCheck(array $params = []): bool
    {
        $payment = Payment::find($params['account']);
        $amount = ($params['orderSum'] ?? 0) * 100;
        $paymentId =  (int)($params['unitpayId'] ?? 0);      
        if (!$payment || $payment->amount != $amount || ($payment->paymentId && $payment->paymentId != $paymentId)) {
            return false;
        }
        $payment->setPaymentId($paymentId);

        return true;
    }

    /**
     * @used
     */
    private static function methodPay(array $params = []): bool
    {
        $unitpayId = $params['unitpayId'] ?? 0;
        $orderSum = $params['orderSum'] ?? 0;
        if (!$unitpayId || !$orderSum) {
            return false;
        }

        $payment = Payment::findByPaymentIdAndAmount($unitpayId, $orderSum * 100);
        if (!$payment) {
            return false;
        }
        if ($payment->status != 'CONFIRMED') {
            $payment->setAsConfirmed();
            PayReceiveService::actionAfterPayment($payment);
        }

        return true;
    }

    /**
     * @used
     */
    private static function methodError(array $params = []): bool
    {
        return true;
    }
}