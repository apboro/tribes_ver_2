<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RobokassaKey extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'shop_id';

    protected $fillable = [
        'shop_id',
        'merchant_login',
        'first_password',
        'second_password',
    ];

    public static function isKeysUsed(
        string $merchantLogin,
        string $firstPassword,
        string $secondPassword,
        ?int $shopIdExclude
    ): bool
    {

        return self::where([
            ['shop_id', '!=', $shopIdExclude],
            ['merchant_login', '=', $merchantLogin],
            ['first_password', '=', $firstPassword],
            ['second_password', '=', $secondPassword],
        ])->exists();
    }
}
