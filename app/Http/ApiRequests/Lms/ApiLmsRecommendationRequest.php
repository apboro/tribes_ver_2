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
 *  tags={"LMS recommendation"},
 *     @OA\Parameter(
 *         name="publication_id",
 *         in="query",
 *         description="Publication Array",
 *         required=false,
 *         @OA\Schema(type="integer", format="int64")
 *     ),
 *     @OA\Parameter(
 *         name="webinar_id",
 *         in="query",
 *         description="Webinar Array",
 *         required=false,
 *         @OA\Schema(type="integer", format="int64")
 *     ),
 * @OA\Response(response=200, description="OK"),
 * @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch"))
 *)
 */
class ApiLmsRecommendationRequest extends ApiRequest
{

  
}
