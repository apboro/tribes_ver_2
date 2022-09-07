<?php

namespace App\Repositories\Telegram;

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
                $dictReaction = $this->dictReactionRepo->getReactionByCode($reaction->reaction);
                if ($dictReaction === null)
                    $dictReaction = $this->dictReactionRepo->saveReaction($reaction->reaction);

                $reactionModel = new TelegramMessageReaction();
                $reactionModel->group_chat_id = $chat_id;
                $reactionModel->telegram_user_id = $reaction->peer_id->user_id;
                $reactionModel->message_id = $message_id;
                $reactionModel->reaction_id = $dictReaction->id;
                $reactionModel->datetime_record = time();
                $reactionModel->save();
            }
        }
    }
}
