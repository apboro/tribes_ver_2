<?php

namespace App\Console\Commands;

use App\Models\TelegramConnection;
use App\Repositories\Telegram\TeleMessageRepositoryContract;
use App\Repositories\Telegram\TelePostRepositoryContract;
use App\Services\Telegram\TelegramMtproto\UserBot;
use App\Services\TelegramLogService;
use Illuminate\Console\Command;

class TelegramMessageRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получить сообщения telegram чата';

    protected $userBot;
    protected $type = ['group', 'channel'];
    protected $messageRepository;
    protected $postRepository;
    protected $telegramLogService;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TeleMessageRepositoryContract $messageRepository, TelePostRepositoryContract $postRepository, TelegramLogService $telegramLogService)
    {
        $this->telegramLogService = $telegramLogService;
        $this->messageRepository = $messageRepository;
        $this->postRepository = $postRepository;
        $this->userBot = new UserBot;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $telegramConnections = TelegramConnection::select('chat_id', 'access_hash', 'isGroup', 'comment_chat_id', 'comment_chat_hash')->where('is_there_userbot', true)->get();
            foreach ($telegramConnections as $connect) {
                $type = $this->getType($connect);

                if ($type === $this->type[0])
                    $this->forGroup($connect, $type);

                if ($type === $this->type[1])
                    $this->forChannel($connect, $type);
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function getType($connect)
    {
        $type = $this->type[1];
        if ($connect->isGroup === true)
            $type = $this->type[0];

        return $type;
    }

    protected function getCommentType($connect)
    {
        $type = $this->type[1];
        if (!$connect->comment_chat_hash)
            $type = $this->type[0];

        return $type;
    }

    protected function forGroup($connect, $type)
    {
        try {
            $telegramMessages = $connect->messages()->get();
            $access_hash = $connect->access_hash ?? null;
            if ($access_hash)
                $type = 'channel';
                
            $chat_id = str_replace('-', '', (str_replace(-100, '', $connect->chat_id)));
            if (!$telegramMessages->first()) {
                $messages = $this->userBot->getMessages($chat_id, $type, $access_hash);
                $this->saveMessage($messages);
            } else {
                $min_id = $connect->messages()->latest()->first()->message_id;
                $messages = $this->userBot->getMessages($chat_id, $type, $access_hash, $min_id);
                $this->saveMessage($messages);
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function forChannel($connect, $type)
    {
        try {
            $telegramPosts = $connect->posts()->get();
            $chat_id = str_replace('-', '', (str_replace(-100, '', $connect->chat_id)));
            if (!$telegramPosts->first()) {
                $messages = $this->userBot->getMessages($chat_id, $type, $connect->access_hash);
                $this->saveMessage($messages);
            } else {
                $min_id = $connect->posts()->latest()->first()->post_id;
                $messages = $this->userBot->getMessages($chat_id, $type, $connect->access_hash, $min_id);
                $this->saveMessage($messages);
                foreach ($telegramPosts as $post) {
                    $this->forComment($connect, $post);
                }
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function forComment($connect, $post)
    {
        try {
            $telegramPostComments = $post->comment()->get();
            $commentType = $this->getCommentType($connect);
            $comment_access_hash = $connect->comment_chat_hash ?? null;
            $comment_chat_id = str_replace('-', '', (str_replace(-100, '', $connect->comment_chat_id)));
            if (!$telegramPostComments->first()) {
                $messages = $this->userBot->getMessages($comment_chat_id, $commentType, $comment_access_hash);
                $this->saveMessage($messages, true);
            } else {
                $min_id = $post->comment()->latest()->first()->message_id;
                $messages = $this->userBot->getMessages($comment_chat_id, $commentType, $comment_access_hash, $min_id);
                $this->saveMessage($messages, true);
            }
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
                        if (isset($message->message))
                            $this->messageRepository->saveChatMessage($message, $isComment);
                    }
                }
            }
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }
}
