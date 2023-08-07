<?php

namespace App\Http\Controllers\APIv3\Lms;


use App\Http\ApiRequests\Lms\ApiLmsRecommendationRequest;
use App\Http\ApiRequests\Lms\ApiLmsPublicationAndWebinarListRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Lms\LmsRecommendationRepository;

class ApiLmsRecommendationController extends Controller
{
    public function getRecommendation(ApiLmsRecommendationRequest $request, LmsRecommendationRepository $lms)
    {
        return ApiResponse::common($lms->getRecommendation());
    }

    public function getPublicationAndWebinarList(ApiLmsPublicationAndWebinarListRequest $request, LmsRecommendationRepository $lms)
    {
        return ApiResponse::common($lms->getPublicationAndWebinarList());
    }

}
