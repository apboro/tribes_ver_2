<?php

namespace App\Repositories\Telegram;

use App\Models\TelegramMessage;

class TeleMessageRepository implements TeleMessageRepositoryContract
{
    public function saveChatMessage($message, $isComment = false)
    {
        if ($isComment === false) {
            if ($message->peer_id->_ === 'peerChannel') {
                $group_chat_id = '-100'.$message->peer_id->channel_id;
                $type = 'channel';
            } else {
                $group_chat_id = '-'.$message->peer_id->chat_id;
                $type = 'group';
            }
        } else {
            if ($message->peer_id->_ === 'peerChannel') {
                $group_chat_id = '-100'.$message->peer_id->channel_id;
                $comment_chat_id = $message->peer_id->channel_id;
                $type = 'channel';
            } else {
                $group_chat_id = '-'.$message->peer_id->chat_id;
                $comment_chat_id = $message->peer_id->chat_id;
                $type = 'group';
            }
        }

        $messageModel = TelegramMessage::firstOrCreate([
            'message_id' => $message->id,
            'group_chat_id' => $group_chat_id,
            'chat_type' => $type ?? 'group',
            'message_date' => $message->date ?? null
        ]);
        $messageModel->post_id = $message->reply_to->reply_to_msg_id ?? null;
        $messageModel->telegram_user_id = $message->from_id->user_id ?? null;
        $messageModel->text = $message->message;
        $messageModel->comment_chat_id = $comment_chat_id ?? null;
        $messageModel->parrent_message_id = $message->reply_to->answer_to_msg_id ?? null;

        $messageModel->save();
    }
}
