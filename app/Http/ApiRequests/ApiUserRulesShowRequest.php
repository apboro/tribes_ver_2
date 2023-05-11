<?php

namespace App\Http\ApiRequests;


/**
 * @OA\Get(path="/api/v3/user-community-rules/{rule_uuid}",
 *     tags={"Chats IF-THEN"},
 *     summary="Show if-then rule",
 *     operationId="if-then-user-rules-list",
 *     security={{"sanctum": {} }},
 *      @OA\Parameter(
 *         name="rule_uuid",
 *         in="path",
 *         description="UUID of if-then rule",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiUserRulesShowRequest extends ApiRequest
{

}