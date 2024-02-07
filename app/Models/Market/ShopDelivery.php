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

    public $timestamps = false;

    protected $table = 'shop_deliveries';

    protected $fillable = [
        'telegram_user_id',
        'address',
        'email',
        'phone',
    ];

    public static function makeByUser(TelegramUser $user, array $delivery, $phone): self
    {
        return self::create([
            'telegram_user_id' => $user->telegram_id,
            'address'          => $delivery[ShopDelivery::KEY_ADDRESS],
            'email'            => $delivery[ShopDelivery::KEY_EMAIL],
            'phone'            => $phone,
        ]);
    }
}
