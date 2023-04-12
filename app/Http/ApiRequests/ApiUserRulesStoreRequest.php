<?php

namespace App\Http\ApiRequests;


/**
 * @OA\POST(
 * path="/api/v3/user-community-rules/store",
 * operationId="Store_user_community_rules",
 * summary= "Store user rules for communities IDs",
 * security= {{"sanctum": {} }},
 * tags= {"User Rules"},
 *     @OA\RequestBody(
 *        @OA\JsonContent(
 *          @OA\Property(property="rules", type="string"),
 *          @OA\Property(property="communities_ids", type="array", @OA\Items(type="integer"))
 *        )
 *     ),
 *     @OA\Response(response=200, description="OK"),
 * )
 */
class ApiUserRulesStoreRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
          'rules' => '',
        ];
   }

}