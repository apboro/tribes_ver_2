<?php

namespace App\Services\Yookassa;

use App\Models\Payment;
use App\Services\Pay\PayReceiveService;
use Illuminate\Support\Facades\Log;
use \YooKassa\Model\NotificationEventType;
use YooKassa\Model\Notification\NotificationFactory;

class Notify
{

    public static function handle(string $requestBody): bool
    {
        $data = json_decode($requestBody, true);

        $factory = new NotificationFactory();
        $notificationObject = $factory->factory($data);
        $responseObject = $notificationObject->getObject();
        
        /*$client = new \YooKassa\Client();
        if (!$client->isNotificationIPTrusted($_SERVER['REMOTE_ADDR'])) {
            Log::debug('Wrong ip of request');
            header('HTTP/1.1 400 Something went wrong');
            exit();
        }*/

        $amount = (int)$responseObject->_amount->_value;
        $paymentId = $responseObject->getId();
        $payment = Payment::findByPaymentIdAndAmount($paymentId, $amount * 100);
        if (!$payment) {
            return false;
        }

        if ($notificationObject->getEvent() === NotificationEventType::PAYMENT_SUCCEEDED) {
            $payment->setAsConfirmed();
            PayReceiveService::actionAfterPayment($payment);
        } elseif ($notificationObject->getEvent() === NotificationEventType::PAYMENT_CANCELED) {
            $payment->setAsCanceled();
        } elseif ($notificationObject->getEvent() === NotificationEventType::PAYMENT_WAITING_FOR_CAPTURE) {

        }
        
        return true;
    }
}