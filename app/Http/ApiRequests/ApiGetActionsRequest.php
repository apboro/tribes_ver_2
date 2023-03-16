<?php

namespace App\Http\ApiRequests;

/**
 * @OA\Get(
 *  path="/api/v3/dictionaries/get_actions_dictionary",
 *  operationId="get_actions_dictionary",
 *  summary="Get actions dictionary",
 *  security={{"sanctum": {} }},
 *  tags={"Dictionaries"},
 *      @OA\Response(response=200, description="OK",
 *      @OA\JsonContent(
 *          @OA\Property(property="type1", type="string"),
 *      )
 *    )
 *)
 */

class ApiGetActionsRequest extends ApiRequest
{

}