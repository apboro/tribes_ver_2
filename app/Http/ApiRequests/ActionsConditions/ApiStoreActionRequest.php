<?php

namespace App\Http\ApiRequests\ActionsConditions;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Post(
 *  path="/api/v3/actions/store",
 *  operationId="store_user_action",
 *  summary="Store user action",
 *  security={{"sanctum": {} }},
 *  tags={"ActionsConditions"},
 *     @OA\RequestBody(
 *      @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *          @OA\Property(property="type_id", description="Тип action из action_dictionary", type="integer", example=1),
 *          @OA\Property(property="user_id", type="integer", example=4),
 *          @OA\Property(property="group_uuid", description="Передаем group_uuid первого condition в первой группе", type="string", example="3299d7881-6a94-cd8b-4f0df15c0-2ecf5a"),
 *          @OA\Property(property="parameter", type="string", example="Hello, World!")
 *      )
 *    )
 *  ),
 *  @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *  )
 */

class ApiStoreActionRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'type_id' => 'required|integer',
            'user_id' => 'required|integer',
            'group_uuid' => 'required|string',
            'parameter' => 'string|nullable',
        ];
    }

}