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
                $dictReaction = $this->dictReactionRepo->getReactionByCode(json_encode($result->reaction));
                if ($dictReaction) {
                    $postReactionModel = TelegramPostReaction::where('chat_id', '-100' . $reaction->peer->channel_id)
                        ->where('post_id', $reaction->msg_id)
                        ->where('reaction_id', $dictReaction->id)
                        ->first();

                    if ($postReactionModel) {
                        $postReactionModel->count = $result->count;
                        $postReactionModel->datetime_record = time();
                        $postReactionModel->save();
                    } else {
                        $postReactionModel = TelegramPostReaction::create([
                            'chat_id' => '-100' . $reaction->peer->channel_id,
                            'post_id' => $reaction->msg_id,
                            'reaction_id' => $dictReaction->id,
                            'count' => $result->count,
                            'datetime_record' => time()
                        ]);
        
                        $telegramPost = $postReactionModel->post()->first();
                        if ($dictReaction->flag_value == 1) {
                            $telegramPost->utility = $telegramPost->utility + 1;
                            $telegramPost->datetime_record_reaction = time();
                            $telegramPost->save();
                        } elseif ($dictReaction->flag_value == 0) {
                            $telegramPost->utility = $telegramPost->utility - 1;
                            $telegramPost->datetime_record_reaction = time();
                            $telegramPost->save();
                        }
                    }
                }
            }
        }
    }
}
