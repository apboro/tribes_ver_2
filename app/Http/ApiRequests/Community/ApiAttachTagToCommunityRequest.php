<?php

namespace App\Http\ApiRequests\Community;


use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *  path="/api/v3/chat-tags/attach",
 *  operationId="chat-tags-attach",
 *  summary="Create and Attach tags to chat",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Tags"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             encoding={
 *                  "tags[]": {
 *                      "explode": true,
 *                  },
 *              },
 *             @OA\Schema(
 *                 @OA\Property(property="tags[]",type="array", @OA\Items(type="string")),
 *                 @OA\Property(property="community_id",type="integer", example=1),
 *             ),
 *         )
 *     ),
 *      @OA\Response(response=200, description="Ok"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *  )
 */
class ApiAttachTagToCommunityRequest extends ApiRequest
{

    public function rules():array
    {
        return [
            'tags'=>'required|array',
            'tags.*'=>'required|string|max:50',
            'community_id'=>'required|integer|exists:communities,id'
        ];
    }

    public function messages(): array
    {
        return [
            'community_id.required'=>$this->localizeValidation('community.id_required'),
            'community_id.integer'=>$this->localizeValidation('community.id_integer'),
            'community_id.exists'=>$this->localizeValidation('community.id_exists')
        ];
    }
}
