<?php


namespace App\Repositories\Telegram;


use App\Models\TelegramConnection;

class TelegramConnectionRepository implements TelegramConnectionRepositoryContract
{
    public function getConnectionById($id)
    {
        return TelegramConnection::find($id);
    }
}