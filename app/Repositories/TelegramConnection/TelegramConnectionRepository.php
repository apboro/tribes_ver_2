<?php


namespace App\Repositories\TelegramConnection;


use App\Models\TelegramConnection;

class TelegramConnectionRepository implements TelegramConnectionRepositoryContract
{
    public function getConnectionById($id)
    {
        return TelegramConnection::find($id);
    }
}