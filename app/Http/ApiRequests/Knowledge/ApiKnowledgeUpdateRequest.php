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
 *         description="
 *          knowledge_status - enum from [draft,published]",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="knowledge_name",type="string"),
 *                 @OA\Property(property="knowledge_status",type="string"),
 *                 @OA\Property(property="question_in_chat_lifetime",type="integer"),
 *                 @OA\Property(property="is_link_publish",type="boolean"),
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
            'knowledge_status' => ['required','string','in:draft,published'],
            'question_in_chat_lifetime' => ['sometimes','nullable','integer'],
            'is_link_publish' => ['sometimes','nullable','boolean'],
        ];
    }
}