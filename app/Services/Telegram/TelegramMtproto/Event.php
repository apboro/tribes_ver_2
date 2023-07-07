<?php

namespace App\Services\Telegram\TelegramMtproto;

use App\Jobs\GetTelegramMessageHistory;
use App\Jobs\SetNewTelegramUsers;
use App\Models\TelegramConnection;
use App\Models\TestData;
use App\Repositories\Telegram\TeleMessageReactionRepositoryContract;
use App\Repositories\Telegram\TeleMessageRepositoryContract;
use App\Repositories\Telegram\TelePostRepositoryContract;
use App\Services\TelegramLogService;

class Event
{

    protected $messageRepository;
    protected $postRepository;
    protected $messageReactionRepo;

    public function __construct(
        TeleMessageRepositoryContract $messageRepository,
        TelePostRepositoryContract $postRepository,
        TeleMessageReactionRepositoryContract $messageReactionRepo
    ) {
        $this->messageRepository = $messageRepository;
        $this->postRepository = $postRepository;
        $this->messageReactionRepo = $messageReactionRepo;
    }


    public function handler($updates)
    {
        $updates = json_decode($updates);
        if (gettype($updates) == 'array') {
            foreach ($updates as $update) {
                $this->getProcessingMethods($update);
            }
        } elseif (gettype($updates) == 'object') {
            $this->getProcessingMethods($updates);
        } else {
            TelegramLogService::staticSendLogMessage(
                'В обработчик событий App\Services\Telegram\TelegramMtproto\Event пришел не массив и не объект.
                 Просмотреть возможные варианты.'
            );
        }
    }

    protected function getProcessingMethods($update)
    {
        $this->newParticipants($update);
        $this->updateChannel($update);
        $this->newGroupMessage($update);
    }

    protected function newParticipants($update)
    {
        try {
            $participants = $update->chat_member ?? null;
            if ($participants) {
//            if ($participants && $update->_ === 'updateChatParticipants') {
//                foreach ($participants->participants as $participant) {
                $isMainBot = $participants->new_chat_member->user->id == config('telegram_user_bot.user_bot.id');
                $isAdmin = $participants->new_chat_member->status == 'administrator';
                    if ($isMainBot && $isAdmin) {
                        $connect = TelegramConnection::where('chat_id', '=', '-' . $participants->chat->id)->first();
                        if ($connect) {
                            $connect->is_there_userbot = true;
                            $connect->userBotStatus = 'administrator';
                            $connect->save();
                        }
                        SetNewTelegramUsers::dispatch($participants->chat->id);
                        GetTelegramMessageHistory::dispatch($participants->chat->id, $this->messageRepository, $this->postRepository, $this->messageReactionRepo)->delay(5);
                    }
//                }
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function updateChannel($update)
    {
        try {
            $admin_rights = isset($update->chats[0]->admin_rights) ? $update->chats[0]->admin_rights : null;
            if (isset($update->updates)) {
                foreach ($update->updates as $newUpdate) {
                    if ($newUpdate->_ === 'updateChannel' && $update->chats[0]->_ === 'channel' && !$admin_rights) {

                        $this->addUserBot($newUpdate, $update->chats[0]);

                    } elseif ($newUpdate->_ === 'updateChannel' && $update->chats[0]->_ === 'channel' && $admin_rights) {

                        $this->updateUserBotStatus($newUpdate->channel_id);
                        dispatch(new SetNewTelegramUsers($newUpdate->channel_id));
                        dispatch(new GetTelegramMessageHistory($newUpdate->channel_id, $this->messageRepository, $this->postRepository, $this->messageReactionRepo))->delay(5);

                    } elseif ($newUpdate->_ === 'updateChannel' && $update->chats[0]->_ === 'channelForbidden') {

                        $this->deleteUserBot($newUpdate);

                    } elseif (
                        $newUpdate->_ === 'updateEditChannelMessage'
                        && isset($newUpdate->message->replies->comments)
                        && $newUpdate->message->replies->comments === true
                    ) {
                        
                        $chat_id = isset($newUpdate->message->peer_id->channel_id) ? $newUpdate->message->peer_id->channel_id : null;
                        $comment_chat = isset($newUpdate->message->replies->channel_id) ? $newUpdate->message->replies->channel_id : null;
                        $this->saveCommentChat($chat_id, $comment_chat, $update);
                        dispatch(new GetTelegramMessageHistory($chat_id, $this->messageRepository, $this->postRepository, $this->messageReactionRepo))->delay(5);

                        if ($newUpdate->message->post == true)
                            $this->postRepository->savePost($newUpdate->message);

                    } elseif (
                        $newUpdate->_ === 'updateNewMessage'
                        && isset($newUpdate->message->action)
                        && $newUpdate->message->action->_ === 'messageActionChatDeleteUser'
                    ) {

                        $chat_id = $newUpdate->message->peer_id->chat_id ?? null;
                        $this->deleteUserBotInGroup($chat_id);

                    } elseif ($newUpdate->_ === 'updateEditChannelMessage') {

                        $this->messageRepository->editMessage($newUpdate->message);

                    } elseif ($newUpdate->_ === 'updateNewChannelMessage' && $newUpdate->message->post == false) {

                        $this->messageRepository->saveChatMessage($newUpdate->message, true);

                    } elseif ($newUpdate->_ === 'updateEditMessage' && isset($newUpdate->message->reactions)) {

                        $this->saveMessageReaction($newUpdate);
                        $this->messageRepository->editMessage($newUpdate->message);

                    } elseif ($newUpdate->_ === 'updateEditMessage') {

                        $this->messageRepository->editMessage($newUpdate->message);

                    } elseif ($newUpdate->_ === 'updateMessageReactions' && isset($newUpdate->reactions->recent_reactions)) {

                        $this->saveCommentMessageReaction($newUpdate);

                    } elseif ($update->_ == 'updateNewMessage') {

                        $this->messageRepository->saveChatMessage($newUpdate->message);

                    } else {
                        continue;
                    }
                }
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function newGroupMessage($update)
    {
        try {
            if (isset($update->_) && $update->_ == 'updateShortChatMessage') {
                $this->messageRepository->saveShortChatMessage($update);
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function addUserBot($newUpdate, $chat)
    {
        try {
            $connect = TelegramConnection::where('chat_id', '-100' . $newUpdate->channel_id)->first();
            if ($connect) {
                $connect->chat_title = $chat->title ?? null;
                $connect->is_there_userbot = true;
                $connect->access_hash = $chat->access_hash;
                $connect->save();
                $this->updateParentChannel($connect, $chat->access_hash);
                dispatch(new GetTelegramMessageHistory($connect->chat_id, $this->messageRepository, $this->postRepository, $this->messageReactionRepo))->delay(5);
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function updateUserBotStatus($chat_id)
    {
        try {
            $connect = TelegramConnection::where('chat_id', '-100' . $chat_id)->first();
            if ($connect) {
                $connect->userBotStatus = 'administrator';
                $connect->save();
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function updateParentChannel($connect, $access_hash)
    {
        $parrentConnect = TelegramConnection::where('comment_chat_id', $connect->chat_id)->first();
        if ($parrentConnect) {
            $parrentConnect->comment_chat_hash = $access_hash;
            $parrentConnect->save();
        }
    }

    protected function deleteUserBot($newUpdate)
    {
        try {
            $connect = TelegramConnection::where('chat_id', '-100' . $newUpdate->channel_id)->first();
            if ($connect) {
                $connect->is_there_userbot = false;
                $connect->save();
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function saveMessageReaction($newUpdate)
    {
        try {
            $chat_id = isset($newUpdate->message->peer_id->chat_id) ? '-' . $newUpdate->message->peer_id->chat_id : null;
            $message_id = isset($newUpdate->message->id) ? $newUpdate->message->id : null;
            $reactions = isset($newUpdate->message->reactions->recent_reactions) ? $newUpdate->message->reactions->recent_reactions : null;
            $this->messageReactionRepo->deleteMessageReactionForChat($chat_id, $message_id);
            $this->messageRepository->resetUtility($chat_id, $message_id);

            if ($reactions) {
                foreach ($reactions as $reaction) {
                    $this->messageReactionRepo->saveOrUpdate($reaction, $chat_id, $message_id);
                }
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function saveCommentMessageReaction($newUpdate)
    {
        try {
            $chat_id = isset($newUpdate->peer->channel_id) ? '-100' . $newUpdate->peer->channel_id : null;
            $message_id = isset($newUpdate->msg_id) ? $newUpdate->msg_id : null;
            $reactions = $newUpdate->reactions->recent_reactions;
            $this->messageReactionRepo->deleteMessageReactionForChat($chat_id, $message_id);
            $this->messageRepository->resetUtility($chat_id, $message_id);

            foreach ($reactions as $reaction) {
                $this->messageReactionRepo->saveOrUpdate($reaction, $chat_id, $message_id);
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function deleteUserBotInGroup(string $chat_id)
    {
        try {
            $connect = TelegramConnection::where('chat_id', '-100' . $chat_id)->orWhere('chat_id', '-' . $chat_id)->first();
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
                $connect->comment_chat_id = '-100' . $comment_chat;
                $connect->comment_chat_hash = $commentHash;
                $connect->save();

                $commentConnect = TelegramConnection::firstOrCreate([
                    'user_id' => $connect->user_id,
                    'telegram_user_id' => $connect->telegram_user_id,
                    'chat_id' => '-100' . $comment_chat
                ]);
                $commentConnect->chat_title = $connect->chat_title;
                $commentConnect->chat_type = 'comment';
                $commentConnect->isGroup = true;
                $commentConnect->is_there_userbot = true;
                $commentConnect->access_hash = $commentHash;
                $commentConnect->save();
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
