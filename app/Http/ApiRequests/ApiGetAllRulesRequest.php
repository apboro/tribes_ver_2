<?php

namespace App\Http\ApiRequests;

use OpenApi\Annotations as OA;

/**
 * @OA\Get(path="/api/v3/all_user_rules",
 *     tags={"Chats Rules"},
 *     summary="Show list of all user rules",
 *     operationId="all-user-rules-list",
 *     security={{"sanctum": {} }},
 *      * @OA\Parameter(
 *         name="offset",
 *         in="query",
 *         description="Begin records from number {offset}",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="limit",
 *         in="query",
 *         description="Total records to display",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *         )
 *     ),
 *     @OA\Parameter(name="rule_title",in="query",description="rule name",required=false,@OA\Schema(type="string")),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiGetAllRulesRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'rule_title' => 'string'
        ];
    }

}