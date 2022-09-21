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

    public function deleteMessageReactionForChat($chat_id)
    {
        TelegramMessageReaction::where('group_chat_id', $chat_id)->delete();
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

            $reactionModel = TelegramMessageReaction::firstOrCreate([
                'group_chat_id' => $chat_id,
                'telegram_user_id' => $reaction->peer_id->user_id,
                'message_id' => $message_id,
            ]);

            $reactionModel->reaction_id = $dictReaction->id;
            $reactionModel->datetime_record = time();
            $reactionModel->save();

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

    protected function zeroingUtility($chat_id, $message_id)
    {
        $tm = TelegramMessage::where('group_chat_id', $chat_id)->where('message_id', $message_id)->first();
        if ($tm) {
            $tm->utility = 0;
            $tm->save();
        }
    }
}
