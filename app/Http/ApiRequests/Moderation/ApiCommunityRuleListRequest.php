<?php

namespace App\Http\ApiRequests\Moderation;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(path="/api/v3/chats/rules",
 *     tags={"Chats Moderation"},
 *     summary="Show list of chat rules",
 *     operationId="chat-rules-list",
 *     security={{"sanctum": {} }},
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiCommunityRuleListRequest extends ApiRequest
{

}
