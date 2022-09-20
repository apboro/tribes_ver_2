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
                    } else {
                        continue;
                    }
                }
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function deleteUserBotInGroup($update)
    {
        try {
            $update = json_decode($update['data'], false) ?? null;
            if ($update) {
                foreach ($update->updates as $newUpdate) {
                    if ($newUpdate->_ === 'updateNewMessage' && $newUpdate->message->action->_ === 'messageActionChatDeleteUser') {
                        $connect = TelegramConnection::where('chat_id', '-100' . $update->chats[0]->id)->first();
                        if ($connect) {
                            $connect->is_there_userbot = false;
                            $connect->save();
                        }
                    } else {
                        continue;
                    }
                }
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }
}
