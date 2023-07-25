<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Collection;

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
        return $this->belongsTo(TelegramUser::class, 'telegram_user_id', 'telegram_id')->withDefault();
    }

    public static function getUsersByCondition(string $direction, int $communityId)
    {
        return self::where($direction, '=' , $communityId)->take(10)->orderBy('reputation_count', 'desc')->get();
    }
}
