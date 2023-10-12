<?php

namespace App\Http\Controllers\APIv3\Webinar;

use App\Http\ApiResponses\ApiResponseSuccess;
use App\Http\Controllers\Controller;
use App\Models\WebinarAnalytics;
use App\Services\TelegramLogService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebinarAnalyticController extends Controller
{

    /**
     * Webhook request  handler
     *
     * after end webinar
     *
     * @param Request $request
     *
     * @return void
     */
    public function handler(Request $request): void
    {
        try{
            $all = $request->collect();

            if($all['event'] === 'export_stats') {
                WebinarAnalytics::saveIncomeStatistic($this->prepare($all));
            }

        } catch (Exception $e) {
            Log::error($e->getMessage());
            TelegramLogService::staticSendLogMessage('Ошибка обработки вебхука от wbnr.ru: '. $e->getMessage());
        }
    }

    /**
     * Prepare
     *
     * @param Collection $all
     * @return mixed
     */
    public function prepare(Collection $all)
    {
        $analytic = $all['stats'][0];
        $analytic['room_id'] = $all['room_id'];

        return $analytic;
    }
}
