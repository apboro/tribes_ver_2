<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramUserCommunity extends Model
{
    use HasFactory;

    protected $table = 'telegram_users_community';
    protected $guarded = [];
    public $timestamps = false;
}
