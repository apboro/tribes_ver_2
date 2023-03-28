<?php

namespace App\Http\ApiRequests\ActionsConditions;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(
 *  path="/api/v3/dictionaries/get_actions_dictionary",
 *  operationId="get_actions_dictionary",
 *  summary="Get actions dictionary",
 *  security={{"sanctum": {} }},
 *  tags={"Dictionaries"},
 *      @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */

class ApiGetActionsDictRequest extends ApiRequest
{

}