<?php

namespace App\Http\ApiRequests\ActionsConditions;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(
 *  path="/api/v3/dictionaries/get_conditions_dictionary",
 *  operationId="get_conditions_dictionary",
 *  summary="Get conditions dictionary",
 *  security={{"sanctum": {} }},
 *  tags={"Dictionaries"},
 *      @OA\Response(response=200, description="OK")
 *)
 */

class ApiGetConditionsDictRequest extends ApiRequest
{

}