<?php

namespace App\Console\Commands;

use App\Models\TelegramConnection;
use App\Models\TelegramUser;
use App\Services\TelegramMainBotService;
use Exception;
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
        $telegram_connections = TelegramConnection::where('botStatus', 'administrator')->get();
        $tele_users = TelegramUser::select('telegram_id')->get();

        foreach ($telegram_connections as $connection) {
            $admins = $this->getAdmins($connection->chat_id);
            if ($admins) {
                foreach ($admins as $admin) {
                    foreach ($tele_users as $tu) {
                        if ($tu->telegram_id === $admin['user']['id']) {
                            $communityId = isset($connection->community->id) ? $connection->community->id : null;
                            if ($communityId) {
                                if ($tu->communities()->find($communityId) === null) {
                                    $tu->communities()->attach($communityId, ['role' => $admin['status'], 'accession_date' => time()]);
                                } else {
                                    $tu->communities()->updateExistingPivot($communityId, [
                                        'role' => $admin['status']
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    protected function getAdmins($chat_id)
    {
        try {
            return TelegramMainBotService::staticGetChatAdministratorsList(config('telegram_bot.bot.botName'), $chat_id);
        } catch (Exception $e) {
            return null;
        }
    }
}
