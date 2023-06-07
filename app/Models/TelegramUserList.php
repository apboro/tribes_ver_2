<?php

namespace App\Models;

use App\Exceptions\Invalid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramUserList extends Model
{
    // temporary fix domain to model
    public const TYPE_WHITE_LIST = 2;
    public const TYPE_MUTE_LIST = 3;
    public const TYPE_BAN_LIST = 4;
    public const SPAMMER = 1;

    public const NAME_TYPE_WHITE = 'whitelisted';
    public const NAME_TYPE_MUTE = 'muted';
    public const NAME_TYPE_BAN = 'banned';
    public const NAME_SPAMMER = 'spammer';

    private const TYPE_NAME_LIST = [
        self::NAME_SPAMMER    => self::SPAMMER,
        self::NAME_TYPE_WHITE => self::TYPE_WHITE_LIST,
        self::NAME_TYPE_MUTE  => self::TYPE_MUTE_LIST,
        self::NAME_TYPE_BAN   => self::TYPE_BAN_LIST,
    ];

    use HasFactory;
    protected $hidden = ['created_at', 'updated_at', 'telegram_id', 'id'];

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
