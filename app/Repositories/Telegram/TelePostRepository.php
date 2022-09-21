<?php

namespace App\Repositories\Telegram;

use App\Models\TelegramPost;

class TelePostRepository implements TelePostRepositoryContract
{

    public function savePost($message)
    {
        $postModel = TelegramPost::firstOrCreate([
            'post_id' => $message->id,
            'channel_id' => '-100'.$message->peer_id->channel_id
        ]);
        $postModel->post_date = $message->date ?? null;
        $postModel->text = $message->message ?? null;

        $postModel->save();
    }

}
