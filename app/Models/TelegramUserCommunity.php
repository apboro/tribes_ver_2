<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $role
 */
class TelegramUserCommunity extends Model
{
    use HasFactory;

    protected $table = 'telegram_users_community';
    protected $guarded = [];
    public $timestamps = false;

    public function community()
    {
        return $this->belongsTo(Community::class);
    }
}
