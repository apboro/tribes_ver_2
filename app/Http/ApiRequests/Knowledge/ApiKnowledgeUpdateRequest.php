<?php


namespace App\Http\ApiRequests\Knowledge;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Put(
 *  path="/api/v3/knowledge/{id}",
 *  operationId="knowledge-update",
 *  summary="Update the specified knowledge",
 *  security={{"sanctum": {} }},
 *  tags={"Knowledge"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of knowledge in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
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
class ApiKnowledgeUpdateRequest extends ApiRequest
{
    public function all($keys = null): array
    {
        $data = parent::all();
        $data['id'] = $this->route('id');

        return $data;
    }

    public function rules(): array
    {
        return [
            'knowledge_name' => ['required','string'],
        ];
    }
}