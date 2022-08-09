<?php


namespace App\Repositories\Telegram;


interface TelegramConnectionRepositoryContract
{
    public function getConnectionById($id);
}