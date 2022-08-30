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
        return $this->belongsTo(TelegramConnection::class, 'channel_id');
    }

    function comment()
    {
        return $this->hasMany(TelegramMessage::class, 'post_id', 'id');
    }

    function reactions()
    {
        return $this->belongsToMany(TelegramDictReaction::class, 'telegram_post_reactions', 'post_id', 'reaction_id')->withPivot(['count', 'datetime_record']);
    }
}
