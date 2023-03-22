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
 *      @OA\Response(response=200, description="OK")
 *    )
 *)
 */

class ApiGetActionsDictRequest extends ApiRequest
{

}