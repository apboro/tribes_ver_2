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
                $this->saveOrUpdate($reaction, $chat_id, $message_id);
            }
        }
    }

    public function saveChannelReaction($reactions, $chat_id, $message_id)
    {
        if (isset($reactions[0]->getReactions->updates[0]->reactions->recent_reactions)) {
            foreach ($reactions[0]->getReactions->updates[0]->reactions->recent_reactions as $reaction) {
                $this->saveOrUpdate($reaction, $chat_id, $message_id);
            }
        }
    }

    protected function saveOrUpdate($reaction, $chat_id, $message_id)
    {
        $dictReaction = $this->dictReactionRepo->getReactionByCode(json_encode($reaction->reaction));
        if ($dictReaction) {
            $reactionModel = TelegramMessageReaction::where('group_chat_id', $chat_id)
                ->where('telegram_user_id', $reaction->peer_id->user_id)
                ->where('message_id', $message_id)->first();

            if ($reactionModel) {
                if ($reactionModel->reaction_id === $dictReaction->id) {
                    return false;
                } else {
                    $reactionModel->reaction_id = $dictReaction->id;
                    $reactionModel->datetime_record = time();
                    $reactionModel->save();
                }
            } else {
                $reactionModel = TelegramMessageReaction::create([
                    'group_chat_id' => $chat_id,
                    'telegram_user_id' => $reaction->peer_id->user_id,
                    'message_id' => $message_id,
                    'reaction_id' => $dictReaction->id,
                    'datetime_record' => time()
                ]);

                $telegramMessage = $reactionModel->message()->first();
                if ($dictReaction->flag_value == 1) {
                    $telegramMessage->utility = $telegramMessage->utility + 1;
                    $telegramMessage->datetime_record_reaction = time();
                    $telegramMessage->save();
                } elseif ($dictReaction->flag_value == 0) {
                    $telegramMessage->utility = $telegramMessage->utility - 1;
                    $telegramMessage->datetime_record_reaction = time();
                    $telegramMessage->save();
                } else {
                    return false;
                }
            }
        }
    }
}
