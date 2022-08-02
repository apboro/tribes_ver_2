<?php

namespace App\Models\Telegram;

use App\Models\TelegramConnection;
use App\Models\TelegramUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelegramMessage extends Model
{
    use HasFactory;

    public function telegramConnection(): BelongsTo
    {
        return $this->belongsTo(TelegramConnection::class, 'chat_id','chat_id');
    }


    public function telegramUser(): BelongsTo
    {
        return $this->belongsTo(TelegramUser::class, 'telegram_user_id','telegram_id');
    }
}
