<?php

namespace App\Http\ApiRequests;


/**
 * @OA\Post(
 *  path="/api/v3/user/community-users/add_to_list",
 *  operationId="community-user-list-add",
 *  summary="Add user to list (black, mute, ban, white)",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Users"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *            mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="telegram_id", type="integer"),
 *                 @OA\Property(property="community_ids", type="array", @OA\Items(type="integer")),
 *                 @OA\Property(property="banned", type="boolean", example="false"),
 *                 @OA\Property(property="muted", type="boolean", example="false"),
 *                 @OA\Property(property="whitelisted", type="boolean", example="false"),
 *                 @OA\Property(property="blacklisted", type="boolean", example="false"),
 *                 @OA\Property(property="is_spammer", type="integer", example=1),
 *             ),
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK",@OA\JsonContent())
 *)
 */
class ApiCommunityUserListAddRequest extends ApiRequest
{

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
