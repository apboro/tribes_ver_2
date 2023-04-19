<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(path="/api/v3/chats/rules",
 *     tags={"Chats Moderation"},
 *     summary="Show list if chat rules",
 *     operationId="chat-rules-list",
 *     security={{"sanctum": {} }},
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiCommunityRuleListRequest extends ApiRequest
{

}
