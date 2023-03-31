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
 *      @OA\MediaType(
 *             mediaType="multipart/form-data",
 *            encoding={
 *                  "community_ids[]": {
 *                      "explode": true,
 *             },
 *          },
 *          @OA\Schema(
 *          @OA\Property(property="community_ids[]", type="array", @OA\Items(type="integer")),
 *          @OA\Property(property="group_uuid", description="", type="string", example="3299d7881-6a94-cd8b-4f0df15c0-2ecf5a"),
 *          @OA\Property(property="condition_id", type="string", example="34"),
 *          @OA\Property(property="group_prefix", type="string", example="and"),
 *          @OA\Property(property="parent_group_id", description="Передаем id ", type="string", example="6"),
 *      )
 *    )
 *  ),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *  )
 */

class ApiAssignRulesToCommunityRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
          'group_uuid'=>'required|string',
          'community_id'=>'required|array',
          'condition_id'=>'required|integer',
          'group_prefix'=>'string',
          'parent_group_id'=>'integer',
        ];
    }

}