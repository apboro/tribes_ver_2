<?php

namespace App\Http\ApiRequests;

use OpenApi\Annotations as OA;

/**
 * @OA\Put(
 *  path="/api/v3/user/community-users/{userId}",
 *  operationId="community-user-list-add",
 *  summary="Add user to list (black, mute, ban, white)",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Users"},
 *     @OA\Parameter(name="userId",in="path",description="ID of user in database",required=true,@OA\Schema(type="integer",format="int64")),
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *            mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="community_ids",type="array",@OA\Items(type="integer")),
 *                 @OA\Property(property="banned", type="boolean", example="false"),
 *                 @OA\Property(property="muted", type="boolean", example="false"),
 *                 @OA\Property(property="whitelisted", type="boolean", example="false"),
 *                 @OA\Property(property="blacklisted", type="boolean", example="false"),
 *                 @OA\Property(property="is_spammer", type="boolean", example="false"),
 *             ),
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK",@OA\JsonContent())
 *)
 */
class ApiCommunityUserListAddRequest extends ApiRequest
{
    public function all($keys = null)
    {
        $data = parent::all();
        $data['telegram_id'] = $this->route('id');
        return $data;
    }


    public function rules(): array
    {
        return [
            'telegram_id' => 'required|integer|exists:telegram_users,telegram_id',
            'community_ids' => 'required|array',
            'community_ids.*' => 'integer|exists:communities,id',
            'banned' => 'boolean',
            'muted' => 'boolean',
            'whitelisted' => 'boolean',
            'blacklisted' => 'boolean',
            'is_spammer' => 'integer'
        ];
    }

    public function messages(): array
    {
        return [
            'telegram_id.required' => $this->localizeValidation('telegram_user.required_telegram_id'),
            'telegram_id.integer' => $this->localizeValidation('telegram_user.integer_telegram_id'),
            'telegram_id.exists' => $this->localizeValidation('telegram_user.exists_telegram_id'),
            'community_ids.required' => $this->localizeValidation('community.id_required'),
            'community_ids.array' => $this->localizeValidation('community.array'),
        ];
    }

}
