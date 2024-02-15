<?php

namespace App\Services\Pay;

use App\Models\Market\ShopOrder;
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
use App\Services\Tinkoff\Bill;
use App\Services\Tinkoff\Payment as Pay;
use Illuminate\Support\Facades\Log;

class PayService
{
    public const SHOP_ORDER_TYPE_NAME = 'shopOrder';

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

    public static function prolongTariff(int $amount, $payFor, User $payer, ?int $telegramId)
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

    public static function buySubscription(int $amount, $payFor, User $payer, ?string $successUrl)
    {
        return self::doPayment($amount, $payFor, $payer, null,  $successUrl, true);
    }

    public static function prolongSubscription(int $amount, $payFor, User $payer)
    {
        return self::doPayment($amount, $payFor, $payer, null,  '', true, true);
    }

    public static function buyProduct(int $amount, $payFor, User $payer, int $telegramId, string $successUrl)
    {
        return self::doPayment($amount, $payFor, $payer, $telegramId, $successUrl);
    }

    public static function doPayment(int $amount, $payFor, ?User $payer, ?int $telegramId = null, ?string  $successUrl = '', ?bool $recurrent = false, ?bool $charged = false)
    {
        $type = self::findType($payFor);
        $accumulation = self::findAccumulation($type, $payFor);
        $payment = self::createPaymentRecord($type, $amount * 100, $payer, $telegramId, $payFor, $accumulation, $charged);
        $orderId = $payment->id . date("_Ymd_His");

        if ($amount == 0 && $type === 'tariff') {
            return self::saveFakeOrder($payment, $payFor, $payer, $orderId, $charged);
        }

        $serviceName = self::getDescriptionByType($type);
        $email = 'manager@spodial.com';
        $phone = '89524365064';
        $quantity = 1;

        return Pay::create()
            ->setPayment($payment)
            ->setOrderId($orderId)
            ->payFor($payFor)
            ->setAccumulation($accumulation)
            ->setPayer($payer)
            ->setRecurrent($recurrent)
            ->setCharged($charged)
            ->setSuccessUrl($successUrl)
            ->setServiceName($serviceName)
            ->setEmail($email)
            ->setPhone($phone)
            ->setQuantity($quantity)
            ->run();
    }
    
    public static function billSubscription(int $subscriptionId)
    {
        $payFor = Subscription::find($subscriptionId);
        if (!$payFor) {
            return false;
        }

        return self::createBill($payFor->price, $payFor, auth()->user());
    }

    public static function createBill(int $amount, $payFor, ?User $payer, ?int $telegramId = null)
    {
        $type = self::findType($payFor);
        $payment = self::createPaymentRecord($type, $amount * 100, $payer, $telegramId, $payFor, null);
        $orderId = $payment->id;
        $serviceName = self::getDescriptionByType($type);
        $quantity = 1; 

        return Bill::create()
            ->setOrderId($orderId)
            ->setPayer($payer)
            ->payFor($payFor)
            ->setPayment($payment)
            ->setServiceName($serviceName)
            ->setQuantity($quantity)
            ->run();
    }

    private static function createPaymentRelations(Payment $payment, $payFor, User $payer)
    {
        if ($payFor) {
            $payFor->payments()->save($payment);
        }
        $payment->payer()->associate($payer)->save();
    }

    private static function createPaymentRecord(string $type, int $amount, User $payer, ?int $telegram_id, $payFor, $accumulation, ?bool $charged = false): Payment
    {
        Log::debug('Функция createPaymentRecord', [
            'type' => $type, 
            'amount' => $amount, 
            'payer' =>  $payer, 
            'telegram_id' =>  $telegram_id, 
            'payFor' => $payFor, 
            'charged' => $charged     
        ]);
        $communityId = self::findCommunityId($type, $payFor);
        $authorId = self::findAuthorId($type, $payFor);
        $rebillId = $charged ? self::findRebillPaymentId($payFor, $payer, $type) : null;
        $payment = new Payment();
        $payment->type = $type;
        $payment->amount = $amount;
        $payment->from = $type === 'donate' ? $payer->user_name : $payer->name;
        $payment->telegram_user_id = $telegram_id ?? null;
        $payment->community_id = $communityId ? $communityId : null;
        $payment->author = $authorId;
        $payment->add_balance = $amount / 100;
        $payment->RebillId = $rebillId;
        if ($accumulation){
            $payment->SpAccumulationId = $accumulation->SpAccumulationId;
        }
        Log::debug('Payment перед созранением', ['payment' => $payment]);
        $payment->save();

        self::createPaymentRelations($payment, $payFor, $payer);

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

        self::createPaymentRelations($payment, $payFor, $payer);
        PayReceiveService::actionAfterPayment($payment);

        return $payment;
    }

    private static function getDescriptionByType(string $type): string
    {
        $names = [
            'tariff'                   => 'Оплата доступа в сообщество Telegram',
            'donate'                   => 'Перевод средств, как безвозмездное пожертвование',
            'publication'              => 'Оплата медиатовара в системе Spodial',
            'webinar'                  => 'Оплата медиатовара в системе Spodial',
            'subscription'             => 'Оплата за использование системы Spodial',
            self::SHOP_ORDER_TYPE_NAME => 'Оплата товара в системе Spodial',
            'default'                  => 'Оплата за использование системы Spodial',
        ];

        return $names[$type] ?? $names['default'];
    }

    private static function findRebillPaymentId($payFor, User $user, string $relation): ?int
    {
        $rebildPayment = Payment::findRebill($payFor->id, $relation, $user->id);

        return $rebildPayment ? $rebildPayment->RebillId : null;
    }

    private static function findAccumulation(string $relation, $payFor): ?Accumulation
    {
        if ($relation === 'tariff' || $relation === 'donate' || $relation === 'course') {
            return Accumulation::findUsersAccumulation($payFor->getAuthor()->id);
        } elseif ($relation === 'publication' || $relation === 'webinar') {
            return Accumulation::findUsersAccumulation($payFor->author->user_id);
        } elseif ($relation === self::SHOP_ORDER_TYPE_NAME) {
            return Accumulation::findUsersAccumulation($payFor->getSellerId());
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
        if ($relation === self::SHOP_ORDER_TYPE_NAME) {
            return  $payFor->getSellerId();
        }

        return null;
    }

    private static function findCommunityId(string $relation, $payFor)
    {
        if ($relation != 'subscription' && $relation != 'publication' && $relation != 'webinar' && $relation != self::SHOP_ORDER_TYPE_NAME) {
            return $payFor->$relation()->first()->community()->first()->id ?? null;
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
            case $payFor instanceof ShopOrder:
                return self::SHOP_ORDER_TYPE_NAME;
            default:
                return false;
        }
    }
}