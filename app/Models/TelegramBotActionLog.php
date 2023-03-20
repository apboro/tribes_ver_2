<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramBotActionLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'action_type_id',
        'community_id',
        'telegram_user_id',
        'action_done'
    ];

    public function actionType(){
        return $this->belongsTo(TelegramBotActionTypes::class, 'action_type_id', 'id');
    }

    public function community(){
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function telegramUser(){
        return $this->belongsTo(TelegramUser::class, 'telegram_user_id', 'id');
    }
}
