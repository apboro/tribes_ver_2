<?php

namespace App\Http\Controllers\APIv3\Semantic;

use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Semantic\ApiCalculateProbabilityRequest;
use App\Repositories\Semantic\SemanticRepository;

class ApiSemanticController extends Controller
{
    private SemanticRepository $semanticRepository;

    public function __construct(SemanticRepository $semanticRepository)
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
