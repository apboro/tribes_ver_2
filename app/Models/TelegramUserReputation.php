<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramUserReputation extends Model
{
    use HasFactory;
    protected $table='telegram_users_reputation';
    protected $guarded = [];

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function telegramUser()
    {
        return $this->belongsTo(TelegramUser::class);
    }
}
