<?php

namespace App\Http\ApiRequests;


/**
 * @OA\POST(
 * path="/api/v3/user-rules/store",
 * operationId="Store_user_rules",
 * summary= "Store user rules",
 * security= {{"sanctum": {} }},
 * tags= {"User Rules"},
 *     @OA\RequestBody(
 *        @OA\JsonContent(
 *          @OA\Property(property="rules", type="string"),
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