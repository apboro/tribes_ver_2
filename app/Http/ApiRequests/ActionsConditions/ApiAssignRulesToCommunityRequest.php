<?php

namespace App\Http\ApiRequests\ActionsConditions;

use App\Http\ApiRequests\ApiRequest;



/**
 * @OA\Post(
 *  path="/api/v3/actions-conditions/assign",
 *  operationId="assign_actions_conditions_to_community",
 *  summary="Assign Rule To Community",
 *  security={{"sanctum": {} }},
 *  tags={"ActionsConditions"},
 *     @OA\RequestBody(
 *      @OA\JsonContent(
 *          @OA\Property(property="community_id", type="integer", example=4),
 *          @OA\Property(property="group_uuid", type="string", example="3299d7881-6a94-cd8b-4f0df15c0-2ecf5a"),
 *      )
 *  ),
 *  @OA\Response(response=200, description="OK")
 *  )
 */

class ApiAssignRulesToCommunityRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
          'group_uuid'=>'required|string',
          'community_id'=>'required|integer'
        ];
    }

}