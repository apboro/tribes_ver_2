<?php

namespace App\Domain\Telegram\Bot;

use Illuminate\Support\Facades\Http;

class PresentationBotDescription
{
    public static function run(string $token): string
    {
        $params = [
            'description' => 'Приветствую! Рад познакомиться, я Бот Spodial!
Я помогу Вам упростить работу с чатами. Для начала работы с ботом выполните команду start'
        ];

        $url = 'https://api.telegram.org/bot' . $token . '/setMyDescription';

        return Http::post($url, $params)->body();
    }
}