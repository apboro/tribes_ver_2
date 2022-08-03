<?php

namespace App\Repositories\Telegram;

use App\Repositories\Telegram\DTO\MessageDTO;

interface TeleMessageRepositoryContract
{
      public function saveMessageForChat(MessageDTO $messageDTO): bool;
}