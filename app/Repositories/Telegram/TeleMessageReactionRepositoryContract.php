<?php


namespace App\Repositories\Telegram;


interface TeleMessageReactionRepositoryContract
{
    public function saveReaction($reactions, $chat_id, $message_id);
    public function saveChannelReaction($reactions, $chat_id, $message_id);
    public function deleteMessageReactionForChat($chat_id, $message_id);
    public function saveOrUpdate($reaction, $chat_id, $message_id);
}