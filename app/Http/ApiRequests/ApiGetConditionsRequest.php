<?php

namespace App\Http\ApiRequests;

/**
 * @OA\Get(
 *  path="/api/v3/dictionaries/get_conditions_dictionary",
 *  operationId="get_conditions_dictionary",
 *  summary="Get conditions dictionary",
 *  security={{"sanctum": {} }},
 *  tags={"Dictionaries"},
 *      @OA\Response(response=200, description="OK",
 *      @OA\JsonContent(
 *          @OA\Property(property="type1", type="string"),
 *          @OA\Property(property="type2", type="string"),
 *          @OA\Property(property="type3", type="string")
 *      )
 *    )
 *)
 */

class ApiGetConditionsRequest extends ApiRequest
{

}