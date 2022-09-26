<?php

namespace App\Console\Commands;

use App\Services\Tariff\TariffService;
use Illuminate\Console\Command;

class SendMessageAboutDeactivatedTariff extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:deactivated:tariff';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command message about expiring deactivated tariff';

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
        $this->tariffServices->sendMessageAboutDeactivatedTariff();
    }
}
