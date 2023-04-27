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
 *          @OA\Property(property="user_rule_id", type="integer"),
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
            'user_rule_id' => 'required|integer',
        ];
    }

}