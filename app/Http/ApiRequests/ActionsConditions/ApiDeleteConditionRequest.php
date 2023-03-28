<?php

namespace App\Http\ApiRequests\ActionsConditions;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Delete(
 *  path="/api/v3/conditions/delete",
 *  operationId="delete_user_condition",
 *  summary="Delete user condition",
 *  security={{"sanctum": {} }},
 *  tags={"ActionsConditions"},
 *     @OA\RequestBody(
 *      @OA\JsonContent(
 *          @OA\Property(property="condition_id", type="integer"),
 *      )
 *  ),
 *  @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *  )
 */
class ApiDeleteConditionRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
          'condition_id'=>'required|integer|exists:conditions,id'
        ];
    }

}