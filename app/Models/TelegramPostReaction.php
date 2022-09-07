<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramPostReaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = ' telegram_post_reactions';

    function post()
    {
        return $this->belongsTo(TelegramPost::class, 'post_id', 'post_id');
    }

    function chat()
    {
        return $this->belongsTo(TelegramConnection::class, 'chat_id', 'chat_id');
    }

    function reaction()
    {
        return $this->belongsTo(TelegramDictReaction::class, 'reaction_id');
    }
}
