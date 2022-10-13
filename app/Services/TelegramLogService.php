<?php

namespace App\Services;

use App\Models\Community;
use App\Services\Telegram\BotInterface\TelegramLogServiceContract;
use App\Services\Telegram\MainBotCollection;
use App\Services\Telegram\Extention\ExtentionApi;

class TelegramLogService implements TelegramLogServiceContract
{

    protected MainBotCollection $botCollect;


    public function __construct(MainBotCollection $botCollection)
    {
        $this->botCollect = $botCollection;
    }

    public function sendLogMessage(string $text)
    {
        $this->getApiCommandsForBot(config('telegram_bot.bot.botName'))->sendMessage([
            'chat_id'        => env('TELEGRAM_LOG_CHAT'),
            'text'           => $text,
            'parse_mode'     => 'HTML'
        ]);
    }

    protected function getApiCommandsForBot(string $nameBot): ExtentionApi
    {
        return $this->botCollect->getBotByName($nameBot)->getExtentionApi();
    }

    public static function staticSendLogMessage(string $text)
    {
        $service = new self(app(MainBotCollection::class));
        $service->sendLogMessage($text);
    }
}
