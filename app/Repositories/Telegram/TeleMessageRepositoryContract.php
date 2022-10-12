<?php

namespace App\Repositories\Telegram;

interface TeleMessageRepositoryContract
{
      public function saveChatMessage($message, $isComment = false);
      public function saveShortChatMessage($message, $isComment = false);
      public function resetUtility($chat_id, $message_id);
      public function editMessage($message);
}