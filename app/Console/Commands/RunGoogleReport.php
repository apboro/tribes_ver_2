<?php

namespace App\Console\Commands;

use App\Services\GoogleSheetsService;
use App\Services\Shop\ShopReport;
use App\Services\Shop\UsersReport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RunGoogleReport extends Command
{
    protected $signature = 'run:googleReport';

    private GoogleSheetsService $googleService;
    private $reports = [];

    public function __construct(GoogleSheetsService $googleService, 
                                ShopReport $shopReport, 
                                UsersReport $usersReport)
    {
        parent::__construct();
        $this->googleService = $googleService;
        $this->reports = ['shops' => $shopReport,
                          'users' => $usersReport];
    }

    public function handle()
    {
        $config = config('google');
        $prepearedReports = [];
        foreach ($config['pageName'] as $key => $pageName) {
            if (isset($this->reports[$key]) && $pageName) {
                $prepearedReports[$key] = $this->reports[$key]->prepareTable();
            }
        }

        try {
            if (config('google.exportShops') === 'ON') {
                $this->googleService->init($config)
                    ->clearPages()
                    ->writePages($prepearedReports);
            } else {
                Log::info('Экспорт магазинов в google docs отключен.');
            }
        } catch (\Exception $e) {
            Log::alert('Ошибка при создании отчета', ['exception' => $e]);
        }

        return 0;
    }
}
