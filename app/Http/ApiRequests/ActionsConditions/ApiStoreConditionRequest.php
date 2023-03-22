<?php

namespace App\Http\ApiRequests\ActionsConditions;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Post(
 *  path="/api/v3/conditions/store",
 *  operationId="store_user_condition",
 *  summary="Store user condition",
 *  security={{"sanctum": {} }},
 *  tags={"ActionsConditions"},
 *     @OA\RequestBody(
 *      @OA\JsonContent(
 *          @OA\Property(property="type_id", type="integer", example=1),
 *          @OA\Property(property="user_id", type="integer", example=4),
 *          @OA\Property(property="group_uuid", type="string", example="3299d7881-6a94-cd8b-4f0df15c0-2ecf5a"),
 *          @OA\Property(property="prefix", description="Can be null || 'and' || 'or'", type="string", example="and"),
 *          @OA\Property(property="parameter", type="string", example="privet")
 *      )
 *  ),
 *  @OA\Response(response=200, description="OK")
 *  )
 */
class ApiStoreConditionRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'type_id' => 'required|integer',
            'user_id' => 'required|integer|exists:users,id',
            'group_uuid' => 'string|nullable',
            'prefix' => 'string|nullable',
            'parameter' => ''
        ];
    }

}