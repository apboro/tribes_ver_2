<?php

namespace App\Http\Controllers\APIv3\Lms;


use App\Http\ApiRequests\Lms\ApiLmsRecommendationRequest;

use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use App\Models\LMSFeedback;
use App\Models\Publication;
use App\Models\Webinar;
use App\Models\VisitedPublication;
use Illuminate\Support\Facades\DB;
use App\Repositories\Lms\LmsRecommendationRepository;


class ApiLmsRecommendationController extends Controller
{
    public function getRecommendation(ApiLmsRecommendationRequest $request, LmsRecommendationRepository $lms)
    {
        return ApiResponse::common($lms->getRecommendation());
    }
}
