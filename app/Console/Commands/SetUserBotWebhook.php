<?php

namespace App\Console\Commands;

use App\Services\Telegram\TelegramMtproto\UserBot;
use Illuminate\Console\Command;

class SetUserBotWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'userBot:setWebhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        (new UserBot())->setWebhook(route('user.bot.webhook'));
        return Command::SUCCESS;

    }
}
