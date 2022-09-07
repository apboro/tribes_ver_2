<?php

namespace App\Repositories\Telegram;

use App\Models\TelegramPostReaction;

class TelePostReactionRepository implements TelePostReactionRepositoryContract
{

    protected $dictReactionRepo;

    public function __construct(TeleDictReactionRepositoryContract $dictReactionRepo)
    {
        $this->dictReactionRepo = $dictReactionRepo;
    }

    public function saveReaction($reactions)
    {
        foreach ($reactions[0]->getReactions->updates as $reaction) {
            foreach ($reaction->reactions->results as $result) {
                $dictReaction = $this->dictReactionRepo->getReactionByCode($result->reaction);
                if ($dictReaction === null) 
                    $dictReaction = $this->dictReactionRepo->saveReaction($result->reaction);
                
                $postReaction = new TelegramPostReaction();
                $postReaction->chat_id = $reaction->peer->channel_id ?? null;
                $postReaction->post_id = $reaction->msg_id ?? null;
                $postReaction->reaction_id = $dictReaction->id;
                $postReaction->count = $result->count;
                $postReaction->datetime_record = time();
            }
        }
    }
}
