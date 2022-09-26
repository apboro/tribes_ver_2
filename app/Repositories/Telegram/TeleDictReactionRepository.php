<?php

namespace App\Repositories\Telegram;

use App\Models\TelegramDictReaction;

class TeleDictReactionRepository implements TeleDictReactionRepositoryContract
{
    public function saveReaction($reaction)
    {
    //    $reaction = TelegramDictReaction::create([
    //         'code' => $reaction,
    //         'flag_value' => 1
    //    ]);
       
    //    return $reaction;
    }

    public function getReactionByCode($code)
    {
        return TelegramDictReaction::where('code', $code)->first();
    }
}