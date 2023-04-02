<?php

namespace App\Http\ApiRequests\Admin;


use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/v3/manager/feed-backs",
 *     operationId="admin-feed-back-show-list",
 *     summary="Show feedbacks from site",
 *     security={{"sanctum": {} }},
 *     tags={"Admin feed back"},
 *
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiManagerFeedBackListRequest extends ApiRequest
{

}
