<?php

namespace App\Http\ApiRequests;

use OpenApi\Annotations as OA;

/**
 * @OA\POST(
 *  path="/api/v3/chats/rules/edit/{id}",
 *  operationId="chat-rules-edit",
 *  summary="Edit chat rules",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Rules"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of chat rule in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\RequestBody(
 *      required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             encoding={
 *                  "community_ids[]": {
 *                      "explode": true,
 *                  },
 *                  "restricted_words[]":{
 *                      "explode": true,
 *                  }
 *              },
 *             @OA\Schema(
 *                 @OA\Property(property="name",type="string",example="test name"),
 *                 @OA\Property(property="content",type="string",example="test description"),
 *                 @OA\Property(property="restricted_words[]",type="array",@OA\Items(type="string")),
 *                 @OA\Property(property="max_violation_times",type="integer",example="10"),
 *                 @OA\Property(property="warning",type="string",example="test warning"),
 *                 @OA\Property(property="action",type="integer",example="1"),
 *                 @OA\Property(property="warning_image",type="file", format="binary"),
 *                 @OA\Property(property="community_ids[]",type="array",@OA\Items(type="integer"))
 *
 *             ),
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiCommunityRuleEditRequest extends ApiRequest
{
    public function all($keys = null)
    {
        $data = parent::all();
        $data['id'] = $this->route('id');

        return $data;
    }


    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:community_rules,id',
            'name' => 'required|string|max:120',
            'content' => 'required|string',
            'restricted_words' => 'required|array',
            'max_violation_times' => 'integer',
            'warning' => 'required|string',
            'community_ids' => 'array',
            'community_ids.*' => 'integer|exists:communities,id',
            'action' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => $this->localizeValidation('chat_rule.id_required'),
            'id.integer' => $this->localizeValidation('chat_rule.id_integer'),
            'id.exists' => $this->localizeValidation('chat_rule.id_exists'),
        ];
    }
}
