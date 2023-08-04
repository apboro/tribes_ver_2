<?php

namespace App\Http\ApiRequests\Lms;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\GET(
 *  path="/api/v3/lms_recommendation",
 *  operationId="lms_recommendation",
 *  summary="Lms recommendation page",
 *  security={{"sanctum": {} }},
 *  tags={},
 *     @OA\Parameter(
 *         name="community_ids[]",
 *         in="query",
 *         description="Community Array",
 *         required=false,
 *         @OA\Schema(type="array",@OA\Items(type="integer"))
 *     ),
 *     @OA\Parameter(
 *         name="webinar_ids[]",
 *         in="query",
 *         description="Webinar Array",
 *         required=false,
 *         @OA\Schema(type="array",@OA\Items(type="integer"))
 *     ),
 * @OA\Response(response=200, description="OK"),
 * @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch"))
 *)
 */
class ApiLmsRecommendationRequest extends ApiRequest
{

  
}
