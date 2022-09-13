<?php

namespace App\Repositories\Telegram;

use App\Models\TelegramMessage;
use App\Models\TelegramMessageReaction;

class TeleMessageReactionRepository implements TeleMessageReactionRepositoryContract
{

    protected $dictReactionRepo;

    public function __construct(TeleDictReactionRepositoryContract $dictReactionRepo)
    {
        $this->dictReactionRepo = $dictReactionRepo;
    }

    public function saveReaction($reactions, $chat_id, $message_id)
    {
        if (isset($reactions[0]->getReactions->reactions)) {
            foreach ($reactions[0]->getReactions->reactions as $reaction) {
                $dictReaction = $this->dictReactionRepo->getReactionByCode(json_encode($reaction->reaction));
                if ($dictReaction === null)
                    $dictReaction = $this->dictReactionRepo->saveReaction($reaction->reaction);

                $reactionModel = TelegramMessageReaction::create([
                    'group_chat_id' => $chat_id,
                    'telegram_user_id' => $reaction->peer_id->user_id,
                    'message_id' => $message_id,
                    'reaction_id' => $dictReaction->id,
                    'datetime_record' => time()
                ]);
                $telegramMessage = $reactionModel->message()->first();
                if ($dictReaction->flag_value === 1) {
                    $telegramMessage->utility = $telegramMessage->utility + 1;
                    $telegramMessage->save();
                } elseif ($dictReaction->flag_value === 0) {
                    $telegramMessage->utility = $telegramMessage->utility - 1;
                    $telegramMessage->save();
                }
                
            }
        }
    }
}
