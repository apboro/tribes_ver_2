<?php

namespace App\Http\ApiRequests\Publication;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\GET(
 *  path="/api/v3/publications",
 *  operationId="publication-list",
 *  summary="List publication",
 *  security={{"sanctum": {} }},
 *  tags={"Publication"},
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiPublicationListRequest extends ApiRequest
{
}
