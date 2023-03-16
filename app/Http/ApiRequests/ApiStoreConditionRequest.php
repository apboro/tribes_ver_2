<?php

namespace App\Http\ApiRequests;

/**
 * @OA\Post(
 *  path="/api/v3/conditions/store",
 *  operationId="store_user_condition",
 *  summary="Store user condition",
 *  security={{"sanctum": {} }},
 *  tags={"Conditions"},
 *     @OA\RequestBody(
 *      @OA\JsonContent(
 *          @OA\Property(property="type_id", type="integer"),
 *          @OA\Property(property="parameter", type="string")
 *      )
 *  ),
 *  @OA\Response(response=200, description="OK",
 *      @OA\JsonContent(
 *          @OA\Property(property="type_id", type="integer"),
 *          @OA\Property(property="parameter", type="string")
 *      )
 *    )
 *)
 */

class ApiStoreConditionRequest extends ApiRequest
{

}