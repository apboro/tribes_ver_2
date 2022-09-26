<?php

namespace App\Repositories\Telegram;

use App\Models\TelegramConnection;
use App\Models\TelegramPost;

class TelePostRepository implements TelePostRepositoryContract
{

    public function savePost($message)
    {
        $connection = TelegramConnection::where('chat_id', '-100' . $message->peer_id->channel_id)->first();
        if ($connection) {
            $postModel = TelegramPost::firstOrCreate([
                'post_id' => $message->id,
                'channel_id' => '-100' . $message->peer_id->channel_id
            ]);
            $postModel->post_date = $message->date;
            $postModel->text = $message->message ?? null;

            $postModel->save();
        }
    }
}
