<?php

namespace App\Console\Commands\Bot;

use App\Domain\Telegram\Bot\PresentationBotDescription;
use Illuminate\Console\Command;

class UpdatePresentationBotDescriptionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_present_bot_description';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command update presentation bot description';

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
        try{
            $botToken = config('telegram_bot.bot.token');
            $result = PresentationBotDescription::run($botToken);

            $this->info('update presentation bot description:' . $result);

            return true;
        }catch (\DomainException $e) {
            $this->error($e->getMessage());
            return  false;
        }
    }
}
