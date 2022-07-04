<?php

namespace App\Services\Telegram;

use App\Services\Telegram\BotInterface\BotCollectionContract;
use Askoldex\Teletant\Settings;
use Illuminate\Log\LogManager;

/**
 * @method MainBot current()
 */
class MainBotCollection extends \Illuminate\Support\Collection implements BotCollectionContract
{
    public function add($item): void
    {
        $this->items[$item['botName']] = new MainBot([
            'setSettings' => $this->settings($item['token']),
            'botName' => $item['botName'],
            'botFullName' => $item['botFullName'],
            'botId' => $item['botId'],
            'token' => $item['token']
        ]);
    }

    public function getBotByName(string $nameBot): MainBot 
    {
        return $this->items[$nameBot];
    }

    protected function settings($token)
    {
        $settings = new Settings($token);
        $settings->setHookOnFirstRequest(false);
        $settings->setLogger(app(LogManager::class));
        return $settings;
    }
}