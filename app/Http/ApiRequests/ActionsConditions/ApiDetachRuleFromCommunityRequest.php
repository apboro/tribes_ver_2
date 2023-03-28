<?php

namespace App\Http\ApiRequests\ActionsConditions;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Post(
 *  path="/api/v3/actions-conditions/detach",
 *  operationId="detach_actions_conditions_from_community",
 *  summary="Detach Rule From Community",
 *  security={{"sanctum": {} }},
 *  tags={"ActionsConditions"},
 *     @OA\RequestBody(
 *      @OA\JsonContent(
 *          @OA\Property(property="community_id", type="integer", example=4),
 *          @OA\Property(property="group_uuid", type="string", example="3299d7881-6a94-cd8b-4f0df15c0-2ecf5a"),
 *      )
 *  ),
 *  @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *  )
 */
class ApiDetachRuleFromCommunityRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'group_uuid'=>'required|string',
            'community_id'=>'required|integer'
        ];
    }

}