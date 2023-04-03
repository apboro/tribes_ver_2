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
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                  @OA\Property(property="type_id", description="Тип condition из condition_dictionary", type="integer", example=1),
 *                  @OA\Property(property="user_id", description="ID владельца аккаунта", type="integer", example=4),
 *                  @OA\Property(property="group_uuid", description="Для первого condition в группе передаем null. Сгенерируется group_uuid. Для следующих передаем group_uuid и parent_id родителя", type="string", example="3299d7881-6a94-cd8b-4f0df15c0-2ecf5a"),
 *                  @OA\Property(property="prefix", description="Передаем null для первого условия в группе для следующих 'and' или 'or'", nullable=true, type="string", example="and"),
 *                  @OA\Property(property="parent_id", description="Для первого condition в группе передаем null, для последующих id родителя", type="integer", nullable=true, example=5),
 *                  @OA\Property(property="parameter", type="string", nullable=true, example="Hello"),
 *             )
 *         )
 *     ),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
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
            'parameter' => '',
            'parent_id'=>'integer|nullable|exists:conditions,id'
        ];
    }

}