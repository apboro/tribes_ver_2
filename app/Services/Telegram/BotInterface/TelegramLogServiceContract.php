<?php


namespace App\Services\Telegram\BotInterface;


use App\Models\Community;
use Askoldex\Teletant\Exception\TeletantException;

interface TelegramLogServiceContract
{
    public function sendLogMessage(string $text);
}