<?php

namespace App\Services\Abs;

use App\Services\Telegram;

abstract class Messenger
{
    public static $platform = [
        'Telegram' => Telegram::class,
    ];
}
