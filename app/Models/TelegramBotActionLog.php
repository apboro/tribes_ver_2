<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
