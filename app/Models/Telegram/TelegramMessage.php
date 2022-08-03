<?php

namespace App\Models\Telegram;

use App\Models\TelegramConnection;
use App\Models\TelegramUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property $message_id
 * @property $telegram_user_id
 * @property $chat_id
 * @property $telegram_date
 * @property $text
 */
class TelegramMessage extends Model
{
    use HasFactory;
    protected $fillable = [
        'message_id ',
        'telegram_user_id',
        'chat_id',
        'telegram_date',
        'text',
    ];
    public $incrementing = false;
    protected $primaryKey = 'message_id';

    public function telegramConnection(): BelongsTo
    {
        return $this->belongsTo(TelegramConnection::class, 'chat_id','chat_id');
    }


    public function telegramUser(): BelongsTo
    {
        return $this->belongsTo(TelegramUser::class, 'telegram_user_id','telegram_id');
    }
}
