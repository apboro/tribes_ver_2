<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramUserList extends Model
{
    use HasFactory;

    protected $fillable = [
        'telegram_id',
        'community_id',
        'type'
    ];

    public function communities()
    {
        return $this->belongsTo(
            Community::class,
            'community_id'
        );
    }

    public function telegramUser()
    {
        return $this->belongsTo(
            TelegramUser::class,
            'telegram_id',
            'telegram_id'
        );
    }

    public function listParameters()
    {
        return $this->belongsToMany(
            ListParameter::class,
            'telegram_user_list_parameters',
            'telegram_id',
            'list_parameter_id',
            'telegram_id'
        );
    }
}
