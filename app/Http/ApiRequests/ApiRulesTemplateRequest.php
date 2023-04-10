<?php

namespace App\Http\ApiRequests;

use OpenApi\Annotations as OA;

/**
 * @OA\Get(path="/api/v3/chats/rules-template",
 *     tags={"Chat Rules"},
 *     summary="Show chat rules template",
 *     operationId="rules-template-show",
 *     security={{"sanctum": {} }},
 *
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiRulesTemplateRequest extends ApiRequest
{
}
