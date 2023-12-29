<?php

namespace App\Models\Market;

use App\Models\TelegramUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopDelivery extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'shop_deliveries';

    protected $fillable = [
        'telegram_user_id',
        'address',
        'email',
        'phone',
    ];

    public static function makeByUser(TelegramUser $user, string $address, $phone): self
    {
        return self::create([
            'telegram_user_id' => $user->telegram_id,
            'address'          => $address,
            'email'            => $user->user->email,
            'phone'            => $phone,
        ]);
    }
}
