<?php


namespace App\Repositories\Telegram;


interface TelePostReactionRepositoryContract
{
    public function saveReaction($id);
}