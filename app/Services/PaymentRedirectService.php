<?php

namespace App\Services;

use App\Enums\PaymentType;
use App\Helper\PseudoCrypt;
use App\Models\Payment;
use App\Models\Publication;
use App\Models\Webinar;

class PaymentRedirectService
{
    public static function buildSuccessUrl(Payment $payment, string $successUrl = null): string
    {
        $redirectUrl = '';
        $part = '';

        switch ($payment->type) {
            case PaymentType::SUBSCRIPTION:
                $part = '/app/subscriptions?payment_result=success';
                break;
            case PaymentType::DONATION:
                $part = '/app/public/donate/thanks';
                break;
            case PaymentType::TARIFF:
                $part = '/app/public/tariff/' . $payment->community->tariff->inline_link . '/thanks?' . http_build_query([
                        'paymentId' => PseudoCrypt::hash($payment->id)
                    ]);
                break;
            case PaymentType::PUBLICATION:
                $publication = Publication::find($payment->payable_id);
                $part = '/courses/member/post/' . $publication->uuid;
                break;
            case PaymentType::WEBINAR:
                $webinar = Webinar::find($payment->payable_id);
                $part = '/courses/member/webinar-preview/' . $webinar->uuid;
                break;
            case PaymentType::SHOP_ORDER && !$successUrl:
                $part = '/orders/order/' . $payment->payable->id;
                break;
            case PaymentType::SHOP_ORDER && $successUrl:
                $isUri = str_contains($successUrl, '/');
                if (!$isUri) {
                    $redirectUrl = self::getMarketUrl() . $successUrl;
                    $part = false;
                } else {
                    $redirectUrl = config('app.frontend_url') . $successUrl;
                }
                break;
            case PaymentType::TON_BOT:
                $redirectUrl = $successUrl;
                break;
            default:
                $part = '/';
                break;
        }

        if ($part) {
            $redirectUrl = $successUrl ?? config('app.frontend_url') . $part;
        }

        return $redirectUrl;
    }

    /**
     * @return string
     */
    public static function getMarketUrl(): string
    {
        return 'https://t.me/' .
            config('telegram_bot.bot.botName') . '/' .
            config('telegram_bot.bot.botName') . '?startapp=' . 'success-';
    }
}