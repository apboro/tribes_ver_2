<?php

namespace App\Http\ApiRequests\Tariffs;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(
 *     path="/api/v3/tariffs",
 *     operationId="get-tariffs-list",
 *     summary="Get list of users tariffs",
 *     security={{"sanctum": {} }},
 *     tags={"Tariffs"},
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiTariffListRequest extends ApiRequest
{


}