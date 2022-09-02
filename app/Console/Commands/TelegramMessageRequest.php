<?php

namespace App\Console\Commands;

use App\Models\TelegramConnection;
use App\Services\Telegram\TelegramMtproto\UserBot;
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
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
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
            $this->telegramLogService->sendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function getType($connect)
    {
        $type = $this->type[1];
        if ($connect->isGroup == true)
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
        $telegramMessages = $connect->messages()->get();
        $access_hash = $connect->access_hash ?? null;
        if (!$telegramMessages->first()) {
            $messages = $this->userBot->getMessages($connect->chat_id, $type, $access_hash);
        } else {
            $min_id = $connect->messages()->latest()->first()->message_id;
            $messages = $this->userBot->getMessages($connect->comment_chat_id, $type, $access_hash, $min_id);
        }
    }

    protected function forChannel($connect, $type)
    {
        $telegramPosts = $connect->posts()->get();

        if (!$telegramPosts->first()) {
            $messages = $this->userBot->getMessages($connect->chat_id, $type, $connect->access_hash);
        } else {
            $min_id = $connect->posts()->latest()->first()->post_id;
            $messages = $this->userBot->getMessages($connect->chat_id, $type, $connect->access_hash, $min_id);

            foreach ($telegramPosts as $post) {
                $this->forComment($connect, $post);
            }
        }
    }

    protected function forComment($connect, $post)
    {
        $telegramPostComments = $post->comment()->get();
        $commentType = $this->getCommentType($connect);
        $comment_access_hash = $connect->comment_chat_hash ?? null;

        if (!$telegramPostComments->first()) {
            $messages = $this->userBot->getMessages($connect->comment_chat_id, $commentType, $comment_access_hash);
        } else {
            $min_id = $post->comment()->latest()->first()->message_id;
            $messages = $this->userBot->getMessages($connect->comment_chat_id, $commentType, $comment_access_hash, $min_id);
        }
    }
}
