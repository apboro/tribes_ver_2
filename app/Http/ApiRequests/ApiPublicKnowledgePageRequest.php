<?php

namespace App\Http\ApiRequests;

/**
 * @OA\Get(path="/api/v3/public/knowledge/{hash}",
 *     tags={"Knowledge"},
 *     summary="Knowledge public page",
 *     operationId="knowledge_public",
 *     security={{"sanctum": {} }},
 *     @OA\Parameter(
 *         name="hash",
 *         in="path",
 *         description="uri_hash of knowledge",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiPublicKnowledgePageRequest extends ApiRequest
{

}