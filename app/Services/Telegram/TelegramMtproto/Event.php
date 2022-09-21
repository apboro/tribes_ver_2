<?php

namespace App\Services\Telegram\TelegramMtproto;

use App\Jobs\SetNewTelegramUsers;
use App\Models\TelegramConnection;
use App\Models\TestData;
use App\Services\TelegramLogService;

class Event
{
    public function handler($update)
    {
        $this->newParticipants($update);
        $this->updateChannel($update);
        $this->deleteUserBotInGroup($update);
    }

    protected function newParticipants($update)
    {
        try {
            $update = json_decode($update['data'][0], false);
            $participants = $update->participants ?? null;

            if ($participants && $update->_ === 'updateChatParticipants') {
                foreach ($participants->participants as $participant) {
                    if ($participant->user_id === config('telegram_user_bot.user_bot.id')) {
                        $connect = TelegramConnection::where('chat_id', '-' . $participants->chat_id)->first();
                        if ($connect) {
                            $connect->is_there_userbot = true;
                            $connect->save();
                        }
                        dispatch(new SetNewTelegramUsers($participants->chat_id))->delay(10);
                    }
                }
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function updateChannel($update)
    {
        try {
            $update = json_decode($update['data'], false) ?? null;
            if ($update) {
                $admin_rights = $update->chats[0]->admin_rights ?? null;

                foreach ($update->updates as $newUpdate) {
                    if ($newUpdate->_ === 'updateChannel' && $update->chats[0]->_ === 'channel' && !$admin_rights) {

                        $connect = TelegramConnection::where('chat_id', '-100' . $newUpdate->channel_id)->first();

                        if ($connect) {
                            $connect->is_there_userbot = true;
                            $connect->access_hash = $update->chats[0]->access_hash;
                            $connect->save();
                        }

                    } elseif ($newUpdate->_ === 'updateChannel' && $update->chats[0]->_ === 'channel' && $admin_rights) {

                        dispatch(new SetNewTelegramUsers($newUpdate->channel_id))->delay(10);

                    } elseif ($newUpdate->_ === 'updateChannel' && $update->chats[0]->_ === 'channelForbidden') {

                        $connect = TelegramConnection::where('chat_id', '-100' . $newUpdate->channel_id)->first();
                        if ($connect) {
                            $connect->is_there_userbot = false;
                            $connect->save();
                        }

                    } elseif (
                        $newUpdate->_ === 'updateEditChannelMessage'
                        && isset($newUpdate->message->replies->comments)
                        && $newUpdate->message->replies->comments === true
                    ) {

                        $chat_id = $newUpdate->message->peer_id->channel_id ?? null;
                        $comment_chat = $newUpdate->message->replies->channel_id ?? null;
                        $this->saveCommentChat($chat_id, $comment_chat, $update);

                    } elseif ($newUpdate->_ === 'updateNewMessage' 
                        && isset($newUpdate->message->action)
                        && $newUpdate->message->action->_ === 'messageActionChatDeleteUser') {

                        $this->deleteUserBotInGroup($update->chats[0]->id);

                    } else {
                        continue;
                    }
                }
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function deleteUserBotInGroup($chat_id)
    {
        try {
            $connect = TelegramConnection::where('chat_id', '-100' . $chat_id)->first();
            if ($connect) {
                $connect->is_there_userbot = false;
                $connect->save();
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function saveCommentChat($chat_id, $comment_chat, $update)
    {
        try {
            $connect = TelegramConnection::where('chat_id', '-100' . $chat_id)->first();
            if ($connect && $connect->comment_chat_id == null) {
                $commentHash = $this->getChatHash($update, $comment_chat);
                $connect->comment_chat_id = $comment_chat;
                $connect->comment_chat_hash = $commentHash;
                $connect->save();

                TelegramConnection::create([
                    'user_id' => $connect->user_id,
                    'telegram_user_id' => $connect->telegram_user_id,
                    'chat_id' => '-100' . $comment_chat,
                    'chat_title' => $connect->chat_title . ' Chat',
                    'chat_type' => 'comment',
                    'isGroup' => true,
                    'is_there_userbot' => true,
                    'access_hash' => $commentHash
                ]);
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function getChatHash($update, $comment_chat)
    {
        try {
            foreach ($update->chats as $chat) {
                if ($chat->id == $comment_chat)
                    return $chat->access_hash;
                else
                    continue;
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }
}
