<?php

namespace App\Http\ApiRequests\Reputation;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(path="/api/v3/chats/rate/{uuid}",
 *     tags={"Chats Reputation"},
 *     summary="Show chat reputation by UUID",
 *     operationId="chats-show-chat-reputation",
 *     security={{"sanctum": {} }},
 *     @OA\Parameter(
 *         name="uuid",
 *         in="path",
 *         description="UUID of chat reputation in database",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiCommunityReputationRuleShowRequest extends ApiRequest
{

}
