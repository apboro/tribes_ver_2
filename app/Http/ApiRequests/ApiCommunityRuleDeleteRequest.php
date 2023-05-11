<?php

namespace App\Http\ApiRequests;

use OpenApi\Annotations as OA;

/**
 * @OA\Delete(
 *  path="/api/v3/chats/rules/{moderation_uuid}",
 *  operationId="delete moderation rule",
 *  summary="Delete Moderation Rule",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Moderation"},
 *  @OA\Parameter(
 *         name="moderation_uuid",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *  @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *  )
 */
class ApiCommunityRuleDeleteRequest extends ApiRequest
{

}