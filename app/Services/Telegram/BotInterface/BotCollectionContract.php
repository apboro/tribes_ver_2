<?php 

namespace App\Services\Telegram\BotInterface;

use App\Services\Telegram\MainBot;

Interface BotCollectionContract
{
    public function add(array $item): void;

    public function getBotByName(string $botName): MainBot;

    public function hasBotByName(string $botName): bool;
}