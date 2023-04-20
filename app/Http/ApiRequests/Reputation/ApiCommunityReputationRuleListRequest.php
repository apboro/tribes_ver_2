<?php

namespace App\Http\ApiRequests\Reputation;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(path="/api/v3/chats/rate",
 *     tags={"Chats Reputation"},
 *     summary="Show list of chat reputation",
 *     operationId="chats-show-list-chat-repuataion",
 *     security={{"sanctum": {} }},
 *     @OA\Response(response=200, description="OK"),
 * )
 */
class ApiCommunityReputationRuleListRequest extends ApiRequest
{
}
