<?php

namespace App\Repositories\Telegram;

use App\Models\TelegramConnection;
use App\Models\TelegramPost;
use App\Services\TelegramLogService;

class TelePostRepository implements TelePostRepositoryContract
{

    public function savePost($message)
    {
        try {
            $connection = TelegramConnection::where('chat_id', '-100' . $message->peer_id->channel_id)->first();
            if ($connection) {
                $postModel = TelegramPost::firstOrCreate([
                    'post_id' => $message->id,
                    'channel_id' => '-100' . $message->peer_id->channel_id
                ]);
                $postModel->post_date = $message->date;
                $postModel->text = $message->message ?? null;
                $postModel->save();

                if (!$connection->comment_chat_id) {
                    $this->initCommentChat($connection, $message);
                }
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function initCommentChat($connection, $message)
    {
        try {
            if (isset($message->replies->channel_id)) {
                $connection->comment_chat_id = '-100' . $message->replies->channel_id;
                $connection->save();

                TelegramConnection::create([
                    'user_id' => $connection->user_id,
                    'telegram_user_id' => $connection->telegram_user_id,
                    'chat_id' => '-100' . $message->replies->channel_id,
                    'chat_title' => null,
                    'chat_type' => 'comment',
                    'isGroup' => true,
                    'is_there_userbot' => false,
                ]);
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }
}
