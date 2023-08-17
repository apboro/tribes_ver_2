<?php

namespace App\Services;

use App\Services\Telegram\BotInterface\TelegramLogServiceContract;
use App\Services\Telegram\Extention\ExtentionApi;
use App\Services\Telegram\MainBotCollection;
use Exception;
use Illuminate\Support\Facades\Log;

class TelegramLogService implements TelegramLogServiceContract
{

    protected MainBotCollection $botCollect;


    public function __construct(MainBotCollection $botCollection)
    {
        $this->botCollect = $botCollection;
    }

    public function sendLogMessage(string $text)
    {
        try {
            $this->getApiCommandsForBot(config('telegram_bot.bot.botName'))->sendMessage([
                'chat_id' => env('TELEGRAM_LOG_CHAT'),
                'text' => $text,
                'parse_mode' => 'HTML'
            ]);
        } catch (Exception $e) {
            Log::channel('telegram-bot-log')->alert('Telegram log trouble: '.$e->getMessage());
        }
    }

    protected function getApiCommandsForBot(string $nameBot): ExtentionApi
    {
        return $this->botCollect->getBotByName($nameBot)->getExtentionApi();
    }

    public static function staticSendLogMessage(string $text)
    {
        try {
            $service = new self(app(MainBotCollection::class));
            $service->sendLogMessage($text);
        } catch (Exception $e) {
            log::info('not send message tr: '. $text);
            Log::channel('telegram-bot-log')->alert('Telegram log trouble: '.$e->getMessage());
        }
    }
}
