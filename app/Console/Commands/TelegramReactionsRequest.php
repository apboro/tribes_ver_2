<?php

namespace App\Console\Commands;

use App\Models\TelegramConnection;
use App\Models\TelegramMessage;
use App\Repositories\Telegram\TeleMessageReactionRepositoryContract;
use App\Repositories\Telegram\TelePostReactionRepositoryContract;
use App\Services\Telegram\TelegramMtproto\UserBot;
use App\Services\TelegramLogService;
use Illuminate\Console\Command;

class TelegramReactionsRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:reactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получить реакции telegram сообщения';

    protected $userBot;
    protected $type = ['group', 'channel'];
    protected $postReactionRepo;
    protected $messageReactionRepo;
    protected $telegramLogService;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TeleMessageReactionRepositoryContract $messageReactionRepo, TelePostReactionRepositoryContract $postReactionRepo, TelegramLogService $telegramLogService)
    {
        $this->messageReactionRepo = $messageReactionRepo;
        $this->postReactionRepo = $postReactionRepo;
        $this->telegramLogService = $telegramLogService;
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

    protected function forGroup($connect)
    {
        try {
            $messages = $connect->messages()->where('flag_observation', true)->get();
            
            if ($messages->first()) {
                $chat_id = str_replace('-', '', (str_replace(-100, '', $connect->chat_id)));
                $this->messageReactionRepo->deleteMessageReactionForChat($chat_id);
                foreach ($messages as $message) {
                    $message->utility = '0';
                    $message->save();
                    if ($connect->access_hash === null) {
                        $limit = 200;
                        $reactions = $this->userBot->getReactions($chat_id, $message->message_id, $limit);
                        $this->messageReactionRepo->saveReaction($reactions, $connect->chat_id, $message->message_id);
                    } else {
                        $reactions = $this->userBot->getChannelReactions($chat_id, [$message->message_id], $connect->access_hash);
                        $this->messageReactionRepo->saveChannelReaction($reactions, $connect->chat_id, $message->message_id);
                    }
                }
            }
        } catch (\Exception $e) {
            $this->telegramLogService->sendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function forChannel($connect)
    {
        try {
            $posts = $connect->posts()->select('post_id')->where('flag_observation', true)->get();
            if ($posts->first()) {
                $postsId = [];
                foreach ($posts as $post) {
                    $postsId[] = $post->post_id;
                }
                $chat_id = str_replace('-', '', (str_replace(-100, '', $connect->chat_id)));
                $reactions = $this->userBot->getChannelReactions($chat_id, $postsId, $connect->access_hash);
                $this->postReactionRepo->saveReaction($reactions);
            }
        } catch (\Exception $e) {
            $this->telegramLogService->sendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function forComment($connect, $post)
    {
        try {
            $messages = $post->comment()->where('flag_observation', true)->get();
            if ($messages->first()) {
                $messagesId = [];
                foreach ($messages as $message) {
                    $messagesId[] = $message->message_id;
                }

                $access_hash = $connect->comment_chat_hash ?? null;
                $chat_id = str_replace('-', '', (str_replace(-100, '', $connect->comment_chat_id)));
                $reactions = $this->userBot->getChannelReactions($chat_id, $messagesId, $access_hash);
                $this->postReactionRepo->saveReaction($reactions);
            }
        } catch (\Exception $e) {
            $this->telegramLogService->sendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }
}
