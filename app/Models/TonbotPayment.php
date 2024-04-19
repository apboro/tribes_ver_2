<?php

namespace App\Models;

use App\Jobs\TonbotWebhookJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOneOrMany;

class TonbotPayment extends Model
{
    protected $guarded = [];
    protected $table = 'tonbot_payments';

    public function getAuthor(): User
    {
        return TelegramUser::findOrCreateUser($this->telegram_receiver_id)->user;
    }

    public function payments(): MorphOneOrMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public static function history(?int $telegramReceiverId, ?int $telegramSenderId): array
    {
        return self::when($telegramReceiverId, fn ($q) => $q->where('telegram_receiver_id', $telegramReceiverId))
            ->when($telegramSenderId, fn ($q) => $q->where('telegram_sender_id', $telegramSenderId))
            ->whereHas('payments', function ($query) {
                $query->where('status', 'CONFIRMED')->orWhere('status', 'COMPLETED');
            })
            ->get()
            ->toArray();
    }

    public static function actionAfterPayment(Payment $payment)
    {
        TonbotWebhookJob::dispatch($payment, 0);
    }

    public static function onChangePayment(Payment $payment): void
    {
        if ($payment->status === 'COMPLETED' || $payment->status === 'REFUNDED') {
            TonbotWebhookJob::dispatch($payment, 0);
        }
    }
}