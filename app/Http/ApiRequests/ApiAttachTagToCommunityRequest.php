<?php

namespace App\Http\ApiRequests;


use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *  path="/api/v3/chat-tags/attach",
 *  operationId="chat-tags-attach",
 *  summary="Attach tags to chat",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Tags"},
 *     @OA\RequestBody(
 *          @OA\JsonContent(
 *                 @OA\Property(property="tag_id",type="integer"),
 *                 @OA\Property(property="community_id",type="integer"),
 *                example={"tag_id": 4, "community_id": 1}
 *      )
 *   ),
 *      @OA\Response(response=200, description="Ok")
 *  )
 */
class ApiAttachTagToCommunityRequest extends ApiRequest
{

    public function rules():array
    {
        return [
            'tag_id'=>'required|integer|exists:tags,id',
            'community_id'=>'required|integer|exists:communities,id'
        ];
    }

    public function messages(): array
    {
        return [
            'tag_id.required' => $this->localizeValidation('tag.id_required'),
            'tag_id.integer' => $this->localizeValidation('tag.id_integer'),
            'tag_id.exists' => $this->localizeValidation('tag.id_exists'),
            'community_id.required'=>$this->localizeValidation('community.id_required'),
            'community_id.integer'=>$this->localizeValidation('community.id_integer'),
            'community_id.exists'=>$this->localizeValidation('community.id_exists')

        ];
    }
}
