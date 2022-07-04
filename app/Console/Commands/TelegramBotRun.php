<?php

namespace App\Console\Commands;

use App\Http\Controllers\TelegramBotController;

use Illuminate\Console\Command;
use Illuminate\Http\Request;

class TelegramBotRun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:bot:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'telegram bot runer';

    protected TelegramBotController $telegramBotController;

    /**
     * Create a new command instance.
     *
     * @param TelegramBotController $telegramBotController
     */
    public function __construct(TelegramBotController $telegramBotController)
    {
        $this->telegramBotController = $telegramBotController;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $request = new Request();
        $this->telegramBotController->index($request);
        $this->comment('Telegram bot started');
        return Command::SUCCESS;
    }
}
