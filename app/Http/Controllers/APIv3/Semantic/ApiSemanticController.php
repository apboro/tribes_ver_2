<?php

namespace App\Http\Controllers\APIv3\Semantic;

use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Semantic\ApiCalculateProbabilityRequest;
use App\Repositories\Statistic\TelegramMessagesSemanticRepository;

class ApiSemanticController extends Controller
{
    private TelegramMessagesSemanticRepository $semanticRepository;

    public function __construct(TelegramMessagesSemanticRepository $semanticRepository)
    {
        $this->semanticRepository = $semanticRepository;
    }

    public function charts(ApiCalculateProbabilityRequest $request)
    {
        $statistics = $this->semanticRepository->calculateProbability($request);

        if (!$statistics) {
            return ApiResponse::error('Не удалось составить статисткику для данного чата');
        }

        return ApiResponse::list()->items($statistics);
    }
}
