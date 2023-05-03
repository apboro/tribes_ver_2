<?php


namespace App\Http\ApiRequests\Knowledge;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(
 *  path="/api/v3/knowledge",
 *  operationId="knowledge-list",
 *  summary="List of knowledges",
 *  security={{"sanctum": {} }},
 *  tags={"Knowledge"},
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiKnowledgeListRequest extends ApiRequest
{
}