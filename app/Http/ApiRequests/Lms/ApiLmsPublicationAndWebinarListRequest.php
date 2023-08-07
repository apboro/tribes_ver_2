<?php

namespace App\Http\ApiRequests\Lms;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\GET(
 *  path="/api/v3/publication_and_webinar_list",
 *  operationId="publication_and_webinar_list",
 *  summary="LMS publications and webinars list",
 *  security={{"sanctum": {} }},
 *  tags={"LMS publications and webinars list"},
 * @OA\Response(response=200, description="OK"),
 * @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch"))
 *)
 */
class ApiLmsPublicationAndWebinarListRequest extends ApiRequest
{

  
}
