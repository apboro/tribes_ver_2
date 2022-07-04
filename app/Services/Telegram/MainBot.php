<?php

namespace App\Services\Telegram;

use Askoldex\Teletant\Bot;
use App\Services\Telegram\Extention\ExtentionApi;
use App\Services\Telegram\BotInterface\BotContract;
use Askoldex\Teletant\Exception\TeletantException;
use Askoldex\Teletant\Log;
use Exception;

class MainBot extends Bot implements BotContract
{
    private ExtentionApi $extentionApi;
    public string $botName;
    public string $botFullName;
    public int $botId;
    private string $token;

    public function __construct(array $settings)
    {
        parent::__construct($settings['setSettings']);
        $this->setExtentionApi(new ExtentionApi($settings['setSettings'], new Log($settings['setSettings']->getLogger()), $settings['token']));
        $this->botName = $settings['botName'];
        $this->botFullName = $settings['botFullName'];
        $this->botId = $settings['botId'];
        $this->token = $settings['token'];
    }

    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return ExtentionApi
     */
    public function getExtentionApi(): ExtentionApi
    {
        return $this->extentionApi;
    } 

    /**
     * @param ExtentionApi $api
     */
    protected function setExtentionApi(ExtentionApi $api): void
    {
        $this->extentionApi = $api;
    }
}
