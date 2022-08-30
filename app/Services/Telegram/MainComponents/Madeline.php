<?php

namespace App\Services\Telegram\MainComponents;

use danog\MadelineProto\Settings;
use danog\MadelineProto\Settings\AppInfo;
use danog\MadelineProto\API;

require_once base_path() . '/lib/Madeline/vendor/autoload.php';

class Madeline
{
    public static function settings()
    {
        $settings = new Settings;
        $settings->setAppInfo(
            (new AppInfo)
                ->setApiId('10661237') // 10661237 // env('TELEGRAM_API_ID')
                ->setApiHash('d3678deb958b924a376e84be969bbc47') // d3678deb958b924a376e84be969bbc47 // env('TELEGRAM_API_HASH')
        );
        $MadelineProto = new API(base_path() . '/lib/session.madeline',  $settings);
        $MadelineProto->botLogin(env('TELEGRAM_BOT_TOKEN'));
        return $MadelineProto;
    }

    public function setSession($nameSession)
    {
        $settings = new Settings;
        $settings->setAppInfo(
            (new AppInfo)
                ->setApiId('10661237') // env('TELEGRAM_API_ID')
                ->setApiHash('d3678deb958b924a376e84be969bbc47') // env('TELEGRAM_API_HASH')
        );
        $MadelineProto = new API(base_path() . '/lib/'. $nameSession .'.madeline',  $settings);
        $MadelineProto->start();
        return $MadelineProto;
    }

}
