<?php

namespace App\Console\Commands;

use App\Models\TelegramConnection;
use App\Models\TelegramUser;
use App\Services\TelegramMainBotService;
use Illuminate\Console\Command;

class CheckNewAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check admin list and add in db';

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
        $telegram_connections = TelegramConnection::select('chat_id')->all();
        $tele_users = TelegramUser::select('telegram_id')->get();

        foreach ($telegram_connections as $connection) {
            $admins = TelegramMainBotService::staticGetChatAdministratorsList(config('telegram_bot.bot.botName'), $connection->chat_id);
            foreach ($admins as $admin) {
                foreach ($tele_users as $tu) {
                    if ($tu->telegram_id === $admin['user']['id']) {
                        if ($tu->communities()->find($connection->community->id) === null) {
                            $tu->communities()->attach($connection->community->id, ['role' => $admin['status'], 'accession_date' => time()]);
                        } else {
                            $tu->communities()->updateExistingPivot($connection->community->id, [
                                'role' => $admin['status']
                            ]);
                        }
                    }
                }
            }
        }
    }
}
