<?php

namespace App\Repositories\Telegram;

interface TeleMessageRepositoryContract
{
      public function saveChatMessage($message, $isComment = false);
}