<?php


namespace App\Repositories\Telegram;


use App\Exceptions\Invalid;
use App\Models\TelegramConnection;

class TelegramConnectionRepository implements TelegramConnectionRepositoryContract
{
    /**
     * @param int $id
     * @return TelegramConnection
     *
     * @throw UnexpectedValueException
     */
    public function getConnectionById(int $id): TelegramConnection
    {
        $connection = TelegramConnection::find($id);

        if (!$connection) {
            Invalid::NullException('TelegramConnection not found with id: '. $id);
        }

        return $connection;
    }
}