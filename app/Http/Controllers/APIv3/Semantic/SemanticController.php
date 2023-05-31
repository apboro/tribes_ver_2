<?php

namespace App\Http\Controllers\APIv3\Semantic;

use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Semantic\CalculateProbabilityRequest;
use App\Repositories\Semantic\SemanticRepository;

class SemanticController extends Controller
{
    private SemanticRepository $semanticRepository;

    public function __construct(SemanticRepository $semanticRepository)
    {
        $this->semanticRepository = $semanticRepository;
    }

    public function calculateProbability(CalculateProbabilityRequest $request)
    {
        $statistics = $this->semanticRepository->calculateProbability($request);

        if (!$statistics) {
            return ApiResponse::error('Не удалось составить статисткику для данного чата');
        }

        return ApiResponse::list()->items($statistics);
    }
}
