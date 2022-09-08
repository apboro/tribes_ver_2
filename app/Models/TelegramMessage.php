<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramMessage extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'telegram_messages';

    function telegramConnection()
    {
        return $this->belongsTo(TelegramConnection::class, 'group_chat_id');
    }

    function telegtamUser()
    {
        return $this->belongsTo(TelegramUser::class, 'telegram_user_id');
    }

    function post()
    {
        return $this->belongsTo(TelegramPost::class, 'post_id');
    }

    function reactions()
    {
        return $this->belongsToMany(TelegramDictReaction::class, 'telegram_message_reactions', 'message_id', 'reaction_id')->withPivot(['telegram_user_id', 'datetime_record']);
    }
}
