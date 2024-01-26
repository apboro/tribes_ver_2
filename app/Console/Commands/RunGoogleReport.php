<?php

namespace App\Console\Commands;

use App\Services\GoogleSheetsService;
use App\Services\Shop\ShopReport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RunGoogleReport extends Command
{
    protected $signature = 'run:googleReport';

    private GoogleSheetsService $googleService;

    private ShopReport $shopReport;

    public function __construct(GoogleSheetsService $googleService, ShopReport $shopReport)
    {
        parent::__construct();
        $this->googleService = $googleService;
        $this->shopReport = $shopReport;
    }

    public function handle()
    {
        try {
            if (config('google.exportShops') === 'ON') {
                $this->googleService->init(config('google'))
                    ->clearPage()
                    ->writePage($this->shopReport->prepareTable());
            } else {
                Log::info('Экспорт магазинов в google docs отключен.');
            }
        } catch (\Exception $e) {
            Log::alert('Ошибка при создании отчета', ['exception' => $e]);
        }

        return 0;
    }
}
