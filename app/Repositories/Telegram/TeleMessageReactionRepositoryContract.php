<?php


namespace App\Repositories\Telegram;


interface TeleMessageReactionRepositoryContract
{
    public function saveReaction($reactions, $chat_id, $message_id);
}