<?php

namespace App\Http\ApiRequests;


/**
 * @OA\Delete(
 * path="/api/v3/user-community-rules",
 * operationId="Delete_user_community_rules",
 * summary= "Delete user rules for communities IDs",
 * security= {{"sanctum": {} }},
 * tags= {"Chats IF-THEN"},
 *     @OA\RequestBody(
 *        @OA\JsonContent(
 *          @OA\Property(property="rule_id", type="integer"),
 *          @OA\Property(property="rules", type="object"),
 *          @OA\Property(property="communities_ids", type="array", @OA\Items(type="integer"))
 *        )
 *     ),
 *     @OA\Response(response=200, description="OK"),
 * )
 */
class ApiUserRulesDeleteRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'rule_id' => 'required',
            'community_ids' => 'array',
            'community_ids.*' => 'integer|exists:communities,id',
        ];
    }

}