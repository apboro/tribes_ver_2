<?php

namespace App\Http\ApiRequests\Reputation;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(path="/api/v3/chats/rate-template",
 *     tags={"Chats Reputation"},
 *     summary="Show chat reputation template",
 *     operationId="reputation-template-show",
 *     security={{"sanctum": {} }},
 *
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiCommunityReputationTemplateRequest extends ApiRequest
{
}

