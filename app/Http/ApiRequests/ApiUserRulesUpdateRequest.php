<?php

namespace App\Http\ApiRequests;


/**
 * @OA\PUT(
 * path="/api/v3/user-community-rules",
 * operationId="Update_user_community_rules",
 * summary= "Update user rules for communities IDs",
 * security= {{"sanctum": {} }},
 * tags= {"Chats IF-THEN"},
 *     @OA\RequestBody(
 *        @OA\JsonContent(
 *          @OA\Property (property="rule_id", type="integer"),
 *          @OA\Property(property="rules", type="object"),
 *          @OA\Property(property="communities_ids", type="array", @OA\Items(type="integer"))
 *        )
 *     ),
 *     @OA\Response(response=200, description="OK"),
 * )
 */
class ApiUserRulesUpdateRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'rule_id' => 'required|integer',
            'rules' => 'required',
            'community_ids' => 'array',
            'community_ids.*' => 'integer|exists:communities,id',
        ];
    }

}