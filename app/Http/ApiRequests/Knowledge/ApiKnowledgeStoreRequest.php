<?php


namespace App\Http\ApiRequests\Knowledge;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Post(
 *  path="/api/v3/knowledge",
 *  operationId="knowledge-add",
 *  summary="Add knowledge",
 *  security={{"sanctum": {} }},
 *  tags={"Knowledge"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="knowledge_name",type="string"),
 *                 ),
 *             ),
 *         ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiKnowledgeStoreRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'knowledge_name' => ['required','string'],
        ];
    }
}