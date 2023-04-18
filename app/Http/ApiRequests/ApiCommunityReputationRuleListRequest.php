<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

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
