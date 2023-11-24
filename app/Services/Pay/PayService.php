<?php

namespace App\Services\Pay;

use App\Services\Tinkoff\Payment as Pay;
use App\Models\DonateVariant;
use App\Models\Publication;
use App\Models\Webinar;
use App\Models\Subscription;
use App\Models\TariffVariant;
use App\Models\Accumulation;
use App\Models\TelegramUser;
use App\Models\User;
use App\Models\Payment;
use App\Helper\PseudoCrypt;

class PayService
{

    public static function buyDonate(int $amount, $variant, ?int $telegramUserId)
    {
        $telegramUser = TelegramUser::where('telegram_id', $telegramUserId)->first();
        $payer = $telegramUser != null ? $telegramUser->user : null;

        return self::doPayment($amount, $variant, $payer, $telegramUserId);
    }

    public static function buyTariff(int $amount, $payFor, User $payer, ?int $telegramId)
    {
        return self::doPayment($amount, $payFor, $payer, $telegramId, '', true, false);
    }

    public static function extendTariff(int $amount, $payFor, User $payer, ?int $telegramId)
    {
        return self::doPayment($amount, $payFor, $payer, $telegramId, '', true, true);
    }

    public static function buyWebinar(int $amount, $payFor, User $payer)
    {
        return self::doPayment($amount, $payFor, $payer);
    }

    public static function buyPublication(int $amount, $payFor, User $payer, string $successUrl)
    {
        return self::doPayment($amount, $payFor, $payer, null,  $successUrl);
    }

    public static function buySubscription(int $amount, $payFor, User $payer, string $successUrl)
    {
        return self::doPayment($amount, $payFor, $payer, null,  $successUrl, true);
    }

    public static function extendSubscription(int $amount, $payFor, User $payer)
    {
        return self::doPayment($amount, $payFor, $payer, null,  '', true, true);
    }

    public static function doPayment(int $amount, $payFor, ?User $payer, ?int $telegramId = null, ?string  $successUrl = '', ?bool $recurrent = false, ?bool $charged = false)
    {
        $type = self::findType($payFor);
        $community = self::findCommunity($type, $payFor);
        $authorId = self::findAuthorId($type, $payFor);
        $accumulation = self::findAccumulation($type, $payFor);
        $rebillId = $charged ? self::findRebillPaymentId($payFor, $payer, $type) : null;
        $payment = self::createPaymentRecord($type, $amount * 100, $payer, $telegramId, $community, $authorId, $rebillId, $accumulation);
        $orderId = $payment->id . date("_Ymd_His"); 

        if ($amount == 0 && $type === 'tariff') {
            return self::saveFakeOrder($payment, $payFor, $payer, $orderId);
        }

        $serviceName = self::getServiceName($type);
        $email = 'manager@spodial.com';
        $phone = '89524365064';
        $quantity = 1;

        return Pay::create()
                ->setPayment($payment)
                ->setOrderId($orderId)
                ->payFor($payFor)
                ->setPayer($payer)
                ->setRecurrent($recurrent)
                ->setCharged($charged)
                ->setSuccessUrl($successUrl)
                ->setServiceName($serviceName)
                ->setEmail($email)
                ->setPhone($phone)
                ->setQuantity($quantity)
                ->pay();
    }

    private static function createPaymentRecord(string $type, int $amount, User $payer, ?int $telegram_id, $community, ?int $authorId, ?int $rebillId, ?int $accumulation): Payment
    {
        $payment = new Payment();
        $payment->type = $type;
        $payment->amount = $amount;
        $payment->from = $type === 'donate' ? $payer->user_name : $payer->name;
        $payment->telegram_user_id = $telegram_id ?? null;
        $payment->community_id = $community ? $community->id : null;
        $payment->author = $authorId;
        $payment->add_balance = $amount / 100;
        $payment->RebillId = $rebillId;
        $payment->SpAccumulationId = $accumulation;
        $payment->save();

        return $payment;
    }

    private static function saveFakeOrder(Payment $payment, $payFor, User $payer, string $orderId): Payment
    {
        $payment->updateRecord([
            'OrderId' => $orderId,
            'paymentId' => rand(1000000000, 9999999999),
            'paymentUrl' => route('payment.success', ['hash' => PseudoCrypt::hash($payment->id)]),
            'response' => 'deprecated',
            'status' => 'CONFIRMED',
            'token' => hash('sha256', $payment->id),
            'error' => null,
            'isNotify' => false,
            'comment' => 'trial'
        ]);

        if ($payFor) {
            $payFor->payments()->save($payment);
        }
        $payment->payer()->associate($payer)->save();

        return $payment;
    }   

    private static function getServiceName(string $type): string
    {
        if ($type=='tariff') {
            return 'Оплата доступа в сообщество Telegram';     
        } elseif ($type=='donate') {
            return 'Перевод средств, как безвозмездное пожертвование';     
        } elseif ($type=='publication') {
            return 'Оплата медиатовара в системе Spodial';     
        } elseif ($type=='webinar') {
            return 'Оплата медиатовара в системе Spodial';     
        } elseif ($type=='subscription') {
            return 'Оплата за использование системы Spodial';     
        } else {
            return 'Оплата за использование системы Spodial';   
        }
    } 

    private static function findRebillPaymentId($payFor, User $user, string $relation): ?int
    {
        $rebildPayment = Payment::findRebillPaymentId($payFor->id, $relation, $user->id);      

        return $rebildPayment ? $rebildPayment->RebillId : null;
    }   

    private static function findAccumulation(string $relation, $payFor): ?Accumulation
    {
        if ($relation === 'tariff' || $relation === 'donate' || $relation === 'course') {
            return Accumulation::findUsersAccumulation($payFor->getAuthor()->id);
        } elseif ($relation === 'publication' || $relation === 'webinar') {
            return Accumulation::findUsersAccumulation($payFor->author->user_id);
        }
        
        return null; 
    }


    private static function findAuthorId(string $relation, $payFor): ?int
    {
        if ($relation == 'tariff' || $relation == 'donate' || $relation == 'course') {
            return $payFor->getAuthor()->id;
        }
        if ($relation == 'publication' || $relation == 'webinar') {
            return  $payFor->author->user_id;
        }

        return null;
    }

    private static function findCommunity(string $relation, $payFor)
    {
        if ($relation != 'subscription' && $relation != 'publication' && $relation != 'webinar') {
            return $payFor->$relation()->first()->community()->first() ?? null;
        }

        return null;
    }

    private static function findType($payFor)
    {
        switch ($payFor) {
            case $payFor instanceof TariffVariant:
                return 'tariff';
            case $payFor instanceof DonateVariant:
                return 'donate';
            case $payFor instanceof Publication:
                return 'publication';
            case $payFor instanceof Webinar:
                return 'webinar';               
            case $payFor instanceof Subscription:
                return 'subscription';
            default:
                return false;
        }  
    }

}