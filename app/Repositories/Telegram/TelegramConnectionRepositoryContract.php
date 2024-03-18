<?php


namespace App\Repositories\Telegram;

use App\Models\TelegramConnection;

interface TelegramConnectionRepositoryContract
{
    public function getConnectionById(int $id): TelegramConnection;
}