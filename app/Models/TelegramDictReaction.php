<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramDictReaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'telegram_dict_reactions';

    function postReactions()
    {
        return $this->hasMany(TelegramPostReaction::class, 'reaction_id', 'id');
    }

    function messageReactions()
    {
        return $this->hasMany(TelegramMessageReaction::class, 'reaction_id', 'id');
    }
}
