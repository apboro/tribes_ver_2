<?php

namespace App\Http\ApiRequests\ActionsConditions;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(
 *  path="/api/v3/rules-dict",
 *  operationId="get_rules_dictionary",
 *  summary="Get rules dictionary",
 *  security={{"sanctum": {} }},
 *  tags={"Dictionaries"},
 *      @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */

class ApiGetRulesDictRequest extends ApiRequest
{

}