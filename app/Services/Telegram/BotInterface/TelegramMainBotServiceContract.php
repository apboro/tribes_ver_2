<?php


namespace App\Services\Telegram\BotInterface;


use App\Models\Community;
use Askoldex\Teletant\Exception\TeletantException;

interface TelegramMainBotServiceContract
{
    public function getApiCommandsForBot(string $nameBot);
    public function run(string $nameBot, string $data);
    public function sendLogMessage(string $text);
    public function sendMessageFromBot(string $botName, int $chatId, string $textMessage, bool $preview, array $keyboard);
    public function sendTariffMessage(string $botName, Community $community);
}