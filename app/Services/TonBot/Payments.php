<?php

namespace App\Services\TonBot;

use App\Models\TelegramUser;
use App\Models\TonbotPayment;
use App\Services\Pay\PayService;

class Payments
{
    public static function create(int $telegramReceiverId, int $telegramSenderId, int $amount, string $successUrl = '')
    {
        $payFor = TonbotPayment::create([
            'telegram_receiver_id' => $telegramReceiverId,
            'telegram_sender_id' => $telegramSenderId,
            'amount' => $amount,
            ]);

        $payer = TelegramUser::findOrCreateUser($telegramSenderId)->user;

        return PayService::buyTonbot($amount / 100, $payFor, $payer, $payFor->telegram_sender_id, $successUrl);
    }   
}