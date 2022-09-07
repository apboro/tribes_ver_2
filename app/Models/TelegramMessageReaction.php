<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramMessageReaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'telegram_message_reactions';

    function message()
    {
        return $this->belongsTo(TelegramMessage::class, 'message_id', 'message_id');
    }

    function chat()
    {
        return $this->belongsTo(TelegramConnection::class, 'group_chat_id', 'chat_id');
    }

    function reaction()
    {
        return $this->belongsTo(TelegramDictReaction::class, 'reaction_id');
    }

    function telegramUser()
    {
        return $this->belongsTo(TelegramUser::class, 'telegram_user_id', 'telegram_id');
    }
}
