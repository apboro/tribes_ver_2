<?php

namespace App\Http\ApiRequests\Publication;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\GET(
 *  path="/api/v3/visited/publications",
 *  operationId="visited-publications-list",
 *  summary="List visited publications",
 *  security={{"sanctum": {} }},
 *  tags={"Publication Visited"},
 *  @OA\Parameter(name="offset",in="query",description="Begin records from number {offset}",required=false,@OA\Schema(type="integer",)),
 *  @OA\Parameter(name="limit",in="query",description="Total records to display",required=false,@OA\Schema(type="integer",)),
 *  @OA\Response(response=200, description="OK")
 *)
 */
class ApiVisitedPublicationListRequest extends ApiRequest
{

}
