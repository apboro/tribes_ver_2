<?php

namespace App\Console\Commands;

use App\Services\Telegram;
use Illuminate\Console\Command;

class Fake2Connect extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:channel:connect';

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
        Telegram::botEnterGroupEvent((int)env('TELEGRAM_TEST_USER') ?? 1269912109, 123456, 'channel', 'Тестовый канал #' . random_int(0,9999));
        Telegram::botGetPermissionsEvent((int)env('TELEGRAM_TEST_USER') ?? 1269912109, 23123, 123456);

        return 0;
    }
}
