<?php

namespace App\Http\Controllers\APIv3\Webinar;

use App\Http\ApiResponses\ApiResponseSuccess;
use App\Http\Controllers\Controller;
use App\Models\WebinarAnalytic;
use App\Services\TelegramLogService;
use Exception;
use Illuminate\Http\JsonResponse;
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(Request $request): JsonResponse
    {
        try{
//            $all = $request->collect();
            $all = file_get_contents("php://input");
            $all = json_decode($all, true);

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

        return response()->json(['success' => 'success'], 200);
    }

    /**
     * Prepare
     *
     * @param Collection $all
     * @return mixed
     */
    private function prepare(array $all): array
    {
        $data = [];

        foreach ($all['stats'] as $user) {
            $user['room_id'] = $all['room_id'];
            $data[] = $user;
        }

        return $data;
    }
}
