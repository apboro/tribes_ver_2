<?php

namespace App\Console\Commands;

use App\Services\Tariff\TariffService;

use Illuminate\Console\Command;

class SendSubscriptionNotice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:subscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to send message about tariff end to subscriber';

    protected $tariffServices;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TariffService $tariffServices)
    {
        parent::__construct();
        $this->tariffServices = $tariffServices;
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->tariffServices->sendMessageAboutEndingTariff();
    }
}