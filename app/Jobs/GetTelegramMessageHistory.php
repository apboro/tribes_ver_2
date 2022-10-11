<?php

namespace App\Jobs;

use App\Models\TelegramConnection;
use App\Models\TestData;
use App\Repositories\Telegram\TeleMessageReactionRepositoryContract;
use App\Services\Telegram\TelegramMtproto\UserBot;
use App\Repositories\Telegram\TeleMessageRepositoryContract;
use App\Repositories\Telegram\TelePostRepositoryContract;
use App\Services\TelegramLogService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetTelegramMessageHistory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $chatId;
    protected $userBot;
    protected $postRepository;
    protected $messageRepository;
    protected $messageReactionRepo;
    protected $limit = 50;

    public function __construct(
        $chatId,
        TeleMessageRepositoryContract $messageRepository,
        TelePostRepositoryContract $postRepository,
        TeleMessageReactionRepositoryContract $messageReactionRepo
    ) {
        $this->chatId = $chatId;
        $this->messageReactionRepo = $messageReactionRepo;
        $this->messageRepository = $messageRepository;
        $this->postRepository = $postRepository;
        $this->userBot = new UserBot;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $telegramConnection = TelegramConnection::select('chat_id', 'access_hash', 'chat_type', 'comment_chat_id', 'comment_chat_hash')
                ->where('is_there_userbot', true)
                ->where('chat_id', '-' . $this->chatId)
                ->orWhere('chat_id', '-100' . $this->chatId)
                ->first();

            if ($telegramConnection && $telegramConnection->chat_type) {
                if ($telegramConnection->chat_type == 'group')
                    $this->forGroup($telegramConnection, 'group');
                elseif ($telegramConnection->chat_type == 'channel')
                    $this->forChannel($telegramConnection, 'channel');
                elseif ($telegramConnection->chat_type == 'comment') 
                    $this->forComment($telegramConnection, 'channel');
                else 
                    TelegramLogService::staticSendLogMessage('GetTelegramMessageHistory - отсуствует chat_type в connections');
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function forGroup($connect, $type)
    {
        try {
            $access_hash = $connect->access_hash ?? null;
            if ($access_hash)
                $type = 'channel';

            $chat_id = str_replace('-', '', (str_replace(-100, '', $connect->chat_id)));

            $messages = $this->userBot->getMessages($chat_id, $type, $access_hash, 0, $this->limit);
            $this->saveMessage($messages);
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function forChannel($connect, $type)
    {
        try {
            $chat_id = str_replace('-', '', (str_replace(-100, '', $connect->chat_id)));

            $messages = $this->userBot->getMessages($chat_id, $type, $connect->access_hash, 0, $this->limit);
            $this->saveMessage($messages);
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function forComment($connect, $type)
    {
        try {
            $comment_access_hash = $connect->access_hash ?? null;
            $comment_chat_id = str_replace('-', '', (str_replace(-100, '', $connect->chat_id)));

            $messages = $this->userBot->getMessages($comment_chat_id, $type, $comment_access_hash, 0, $this->limit);
            
            $this->saveMessage($messages, true);
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function saveMessage($messages, $isComment = false)
    {
        try {
            if (isset($messages[0]->messages->messages)) {
                foreach ($messages[0]->messages->messages as $message) {
                    if ($message->post === true) {
                        $this->postRepository->savePost($message);
                    } else {
                        if (isset($message->message)) {
                            $this->messageRepository->saveChatMessage($message, $isComment);

                            if (isset($message->reactions->recent_reactions)) {
                                $this->saveMessageReaction($message); 
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function saveMessageReaction($message)
    {
        try {
            if ($message->peer_id->_ == 'peerChannel') {
                $chat_id = isset($message->peer_id->channel_id) ? '-100' . $message->peer_id->channel_id : null;
            } else {
                $chat_id = isset($message->peer_id->chat_id) ? '-' . $message->peer_id->chat_id : null;
            }
            
            $message_id = isset($message->id) ? $message->id : null;
            $reactions = $message->reactions->recent_reactions;
            
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
}
