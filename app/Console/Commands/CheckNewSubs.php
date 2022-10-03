<?php

namespace App\Console\Commands;

use App\Jobs\SetNewTelegramUsers;
use App\Models\Community;
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use Exception;
use Illuminate\Console\Command;

class CheckNewSubs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:new_subs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Проверка новых подписчиков в канале.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
            $communities = Community::whereHas('connection', function ($q) {
                $q->where('chat_type', 'channel')->where('is_there_userbot', true);
            })->get();
            foreach ($communities as $community) {
                $time = time();
                while (true) {
                    if (time() > $time) {
                        try {
                            $membersOrigin = TelegramMainBotService::staticGetChatMemberCount(config('telegram_bot.bot.botName'), $community->connection->chat_id);
                        } catch (Exception $e) {
                            $membersOrigin = null;
                        }
                        
                        $membersIdent = $community->followers->count();
                        if ($membersOrigin != $membersIdent) {
                            dispatch(new SetNewTelegramUsers($community->connection->chat_id));
                        }
                        break;
                    }
                }
            }
    }
}
