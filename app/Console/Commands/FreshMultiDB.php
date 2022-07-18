<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class FreshMultiDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'base:fresh';

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
        $outputStyle = new OutputFormatterStyle('white', '#ff0000', ['bold', 'blink']);
        $this->output->getFormatter()->setStyle('fire', $outputStyle);

        //todo  добавить обновление app()->environmentFile('.env.testing');
        // \NunoMaduro\Collision\Adapters\Laravel\Commands\TestCommand::handle

        if(env('APP_ENV') == 'production'){
            $this->output->writeln('<fire>ОШИБКА</fire> Выполнять сброс базы на проде запрещено');
            return 0;
        }

        Artisan::call('db:wipe --database=main');
        Artisan::call('db:wipe --database=knowledge');
        $this->output->writeln('<fire>База очищена</fire>');
        Artisan::call('migrate:fresh');
        $this->output->writeln('<fire>Миграции накачены</fire>');

        Artisan::call('db:seed');
        $this->output->writeln('<fire>Сиды посеяны</fire>');

        return 0;
    }
}
