<?php

namespace App\Http\ApiRequests;


/**
 * @OA\Delete(
 * path="/api/v3/chats/rate/{reputation_rule_uuid}",
 *  operationId="delete_reputation_rule",
 *  summary="Delete reputation rule",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Reputation"},
 *     @OA\Parameter(
 *         name="reputation_rule_uuid",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *  )
 */
class ApiDeleteReputationRuleRequest extends ApiRequest
{

}