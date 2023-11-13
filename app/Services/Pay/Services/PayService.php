<?php

namespace App\Services\Pay\Services;

use App\Services\Tinkoff\Payment as Pay;

class PayService
{
    public static function donate(int $amount, $variant, int $telegramUserId)
    {
        $p = new Pay();
        return $p->amount($amount * 100)->payFor($variant)->payer($telegramUserId)->pay();
    }
}