<?php

namespace App\Http\Controllers\APIv3\Analytics;

use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Log;
use Throwable;

class CoursesAnalyticsController extends Controller
{
    public function getReaders(Request $request)
    {
        try {
            /** @var User $user */
            $user = $request->user();
            $visitStatistic = $user->getContentVisitStatistic();

            return ApiResponse::common(new JsonResponse($visitStatistic));

        } catch (Throwable $exception) {
            Log::error($exception->getMessage());

            return ApiResponse::error($exception->getMessage());
        }
    }
}