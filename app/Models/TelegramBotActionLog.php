<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $user_name
 * @property mixed $telegramUser
 * @property mixed $event
 * @property mixed $action
 * @property mixed $created_at
 * @property mixed $telegramConnections
 */
class TelegramBotActionLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'chat_id',
        'telegram_id',
        'action',
        'event',
    ];


    public function telegramConnections(){
        return $this->belongsTo(TelegramConnection::class, 'chat_id', 'chat_id');
    }

    public function telegramUser(){
        return $this->belongsTo(TelegramUser::class, 'telegram_id', 'telegram_id');
    }
}
