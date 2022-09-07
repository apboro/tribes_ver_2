<?php

namespace App\Repositories\Telegram;

interface TelePostRepositoryContract
{
    public function savePost($message);
}