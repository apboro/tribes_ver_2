<?php

namespace App\Repositories\Telegram;

use App\Models\TelegramPost;

class TelePostRepository implements TelePostRepositoryContract
{

    public function savePost($message)
    {
        $postModel = new TelegramPost();
        $postModel->channel_id = $message->peer_id->channel_id;
        $postModel->post_id = $message->id;
        $postModel->post_date = $message->date ?? null;
        $postModel->text = $message->message ?? null;

        $postModel->save();
    }

}
