<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramDictReaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'telegram_dict_reactions';

    function posts()
    {
        return $this->belongsToMany(TelegramPost::class, 'telegram_post_reactions', 'reaction_id', 'post_id')->withPivot(['count', 'datetime_record']);
    }

    function messages()
    {
        return $this->belongsToMany(TelegramMessage::class, 'telegram_message_reactions', 'reaction_id', 'message_id')->withPivot(['telegram_user_id', 'datetime_record']);
    }
}
