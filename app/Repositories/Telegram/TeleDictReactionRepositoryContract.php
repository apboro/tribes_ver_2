<?php


namespace App\Repositories\Telegram;


interface TeleDictReactionRepositoryContract
{
    public function saveReaction($reaction);
    public function getReactionByCode($code);
}