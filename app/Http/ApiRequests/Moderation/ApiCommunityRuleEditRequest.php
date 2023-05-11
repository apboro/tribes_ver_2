<?php

namespace App\Http\ApiRequests\Moderation;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\POST(
 *  path="/api/v3/chats/rules/edit/{uuid}",
 *  operationId="chat-rules-edit",
 *  summary="Edit chat rules",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Moderation"},
 *     @OA\Parameter(
 *         name="uuid",
 *         in="path",
 *         description="UUID of chat rule in database",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
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
 *                 @OA\Property(property="content_image",type="file", format="binary"),
 *                 @OA\Property(property="restricted_words[]",type="array",@OA\Items(type="string")),
 *                 @OA\Property(property="max_violation_times",type="integer",example="10"),
 *                 @OA\Property(property="warning",type="string",example="test warning"),
 *                 @OA\Property(property="warning_image",type="file", format="binary"),
 *                 @OA\Property(property="user_complaint_image",type="file", format="binary"),
 *                 @OA\Property(property="action",type="integer",example="1"),
 *                 @OA\Property(property="complaint_text",type="string"),
 *                 @OA\Property(property="quiet_on_restricted_words",type="boolean"),
 *                 @OA\Property(property="quiet_on_complaint",type="boolean"),
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
            'name' => 'required|string|max:120',
            'content' => 'string|nullable',
            'content_image' => 'image|nullable',
            'restricted_words' => 'array|nullable',
            'max_violation_times' => 'nullable|integer',
            'warning' => 'nullable|string',
            'user_complaint_image' => 'image|nullable',
            'community_ids' => 'array',
            'community_ids.*' => 'integer|exists:communities,id',
            'action' => 'nullable|integer',
            'complaint_text' => 'string|max:1500|nullable',
            'quiet_on_restricted_words' => 'required|boolean',
            'quiet_on_complaint' => 'required|boolean'
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

    public function prepareForValidation(): void
    {
        $this->merge([
            'quiet_on_restricted_words' => $this->toBoolean($this->quiet_on_restricted_words)
        ]);
        $this->merge([
            'quiet_on_complaint' => $this->toBoolean($this->quiet_on_complaint)
        ]);
    }


    /**
     * Convert to boolean
     *
     * @param $booleable
     * @return boolean
     */
    private function toBoolean($booleable)
    {
        return filter_var($booleable, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }

}
