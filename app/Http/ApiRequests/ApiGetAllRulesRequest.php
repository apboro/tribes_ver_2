<?php

namespace App\Http\ApiRequests;

/**
 * @OA\Get(path="/api/v3/all_user_rules",
 *     tags={"Chats Rules"},
 *     summary="Show list of all user rules",
 *     operationId="all-user-rules-list",
 *     security={{"sanctum": {} }},
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiGetAllRulesRequest extends ApiRequest
{

}