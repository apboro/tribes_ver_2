<?php


namespace App\Http\ApiRequests\Knowledge;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Delete(
 *  path="/api/v3/knowledge/{id}",
 *  operationId="knowledge-delete",
 *  summary="Delete the specified knowledge",
 *  security={{"sanctum": {} }},
 *  tags={"Knowledge"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of related knowledge in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiKnowledgeDeleteRequest extends ApiRequest
{
    public function all($keys = null): array
    {
        $data = parent::all();
        $data['id'] = $this->route('id');

        return $data;
    }
}