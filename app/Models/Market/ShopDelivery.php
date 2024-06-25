<?php

namespace App\Models\Market;

use App\Models\TelegramUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopDelivery extends Model
{
    use HasFactory;

    public const KEY_ADDRESS = 'address';
    public const KEY_EMAIL = 'email';
    public const KEY_FULL_NAME = 'full_name';
    public const KEY_TRACK_ID = 'track_id';
    public const DELIVERY_SUM = 'delivery_sum';

    public $timestamps = false;

    protected $table = 'shop_deliveries';

    protected $fillable = [
        'telegram_user_id',
        'address',
        'email',
        'phone',
        'full_name',
        'track_id',
        'delivery_sum',
    ];

    public static function makeByUser(TelegramUser $user, array $delivery, $phone): self
    {
        return self::create([
            'telegram_user_id' => $user->telegram_id,
            'address'          => $delivery[self::KEY_ADDRESS],
            'email'            => $delivery[self::KEY_EMAIL],
            'full_name'        => $delivery[self::KEY_FULL_NAME],
            'track_id'         => $delivery[self::KEY_TRACK_ID],
            'delivery_sum'     => $delivery[self::DELIVERY_SUM],
            'phone'            => $phone,
        ]);
    }
}
