<?php

namespace App\Http\Controllers\APIv3\Webinar;

use App\Http\ApiResponses\ApiResponseSuccess;
use App\Http\Controllers\Controller;
use App\Models\WebinarAnalytic;
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
                $statisticUsersList = $this->prepare($all);
                foreach($statisticUsersList as $user) {
                    WebinarAnalytic::saveIncomeStatistic($user);
                }
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
    private function prepare(Collection $all): array
    {
        $data = [];

        foreach ($all['stats'] as $user) {
            $user['room_id'] = $all['room_id'];
            $data[] = $user;
        }

        return $data;
    }
}
