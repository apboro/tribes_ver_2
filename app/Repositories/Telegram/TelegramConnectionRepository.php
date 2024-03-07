<?php


namespace App\Repositories\Telegram;


use App\Models\TelegramConnection;

class TelegramConnectionRepository implements TelegramConnectionRepositoryContract
{
    public function getConnectionById($id): ?TelegramConnection
    {
        return TelegramConnection::find($id);
    }
}