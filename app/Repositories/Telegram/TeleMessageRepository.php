<?php

namespace App\Repositories\Telegram;

use App\Models\Telegram\TelegramMessage;
use App\Repositories\Telegram\DTO\MessageDTO;

class TeleMessageRepository implements TeleMessageRepositoryContract
{

    public function saveMessageForChat(MessageDTO $messageDTO): bool
    {
        $messageModel = new TelegramMessage([
            'telegram_user_id' => $messageDTO->telegram_user_id,
            'chat_id' => $messageDTO->chat_id,
            'telegram_date' => $messageDTO->telegram_date,
            'text' => $messageDTO->text,
        ]);

        $messageModel->message_id = $messageDTO->message_id;


        return $messageModel->save();
    }
}