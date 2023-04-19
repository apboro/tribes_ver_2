<?php

namespace App\Http\ApiRequests;

use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *  path="/api/v3/chats/rules",
 *  operationId="chat-rules-add",
 *  summary="Add chat rules",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Moderation"},
 *     @OA\RequestBody(
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
 *                 @OA\Property(property="action",type="integer",example="1"), *
 *                 @OA\Property(property="community_ids[]",type="array",@OA\Items(type="integer"))
 *
 *             ),
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiCommunityRuleStoreRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:120',
            'content' => 'required|string',
            'content_image'=>'image|mimetypes:image/jpeg,image/png',
            'restricted_words' => 'required|array',
            'max_violation_times' => 'integer',
            'warning' => 'required|string',
            'warning_image' => 'image|mimetypes:image/jpeg,image/png',
            'user_complaint_image'=>'image|mimetypes:image/jpeg,image/png',
            'community_ids' => 'array',
            'community_ids.*' => 'integer|exists:communities,id',
            'action' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
        ];
    }
}
