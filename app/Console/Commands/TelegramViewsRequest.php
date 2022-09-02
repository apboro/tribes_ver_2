<?php

namespace App\Console\Commands;

use App\Models\TelegramConnection;
use App\Services\Telegram\TelegramMtproto\UserBot;
use Illuminate\Console\Command;

class TelegramViewsRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:views';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получить просмотры telegram сообщения в канале';

    protected $userBot;
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
                $posts = $connect->posts()->select('post_id')->where('flag_observation', true)->get();
                $postsId = [];
                foreach ($posts as $post) {
                    $postsId[] = $post->post_id;
                }

                $views = $this->userBot->getMessagesViews($connect->chat_id, 'channel', $postsId, $connect->access_hash);
            }
        } catch (\Exception $e) {
            $this->telegramLogService->sendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }
}
