<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramPost extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'telegram_posts';

    function telegramConnection()
    {
        return $this->belongsTo(TelegramConnection::class, 'channel_id', 'chat_id');
    }

    function comment()
    {
        return TelegramMessage::where('post_id', $this->post_id)->where('comment_chat_id', $this->telegramConnection->comment_chat_id);   
    }

    function reactions()
    {
        return TelegramPostReaction::where('post_id', $this->post_id)->where('chat_id', $this->channel_id)->first();
    }

    function views()
    {
        return TelegramPostViews::where('post_id', $this->post_id)->where('chat_id', $this->channel_id)->first();
    }
}
