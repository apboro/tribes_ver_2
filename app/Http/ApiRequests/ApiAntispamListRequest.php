<?php

namespace App\Http\ApiRequests;

use OpenApi\Annotations as OA;

/**
 * @OA\Get(path="/api/v3/antispam",
 *  path="/api/v3/antispam",
 *  operationId="antispam-list",
 *  summary="List of antispams",
 *  security={{"sanctum": {} }},
 *  tags={"Antispam"},
 *
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiAntispamListRequest extends ApiRequest
{
}
