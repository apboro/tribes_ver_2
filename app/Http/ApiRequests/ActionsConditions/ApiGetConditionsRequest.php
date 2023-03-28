<?php

namespace App\Http\ApiRequests\ActionsConditions;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(
 *  path="/api/v3/conditions/getList",
 *  operationId="get_conditions_list",
 *  summary="Get conditions list",
 *  security={{"sanctum": {} }},
 *  tags={"ActionsConditions"},
 *      @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */

class ApiGetConditionsRequest extends ApiRequest
{

}