<?php


namespace App\Http\ApiRequests\Knowledge;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Post (
 *  path="/api/v3/knowledge/bind-communities",
 *  operationId="knowledge-bind-communities",
 *  summary="Bind knowledge to communities",
 *  security={{"sanctum": {} }},
 *  tags={"Knowledge"},
 *         @OA\Parameter(
 *         name="XDEBUG_SESSION_START",
 *         in="query",
 *         description="uri_hash of knowledge",
 *         required=true,
 *          example="PHPSTORM",
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="knowledge_id",type="integer"),
 *                 @OA\Property(property="community_ids",type="array",  @OA\Items(type="integer")),
 *                 ),
 *             ),
 *         ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiKnowledgeBindToCommunityRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'knowledge_id' => ['required','integer'],
            'community_ids' => ['array'],
        ];
    }
}