<?php

namespace App\Http\ApiRequests\Community;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;


/**
 * @OA\Post(
 *  path="/api/v3/user/community-users/detach",
 *  operationId="community-users-detach",
 *  summary="Detach user from community",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Users"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(
 *                          property="telegram_id",
 *                          type="integer",
 *                          example=123
 *                 ),
 *                 @OA\Property(
 *                          property="community_id",
 *                          type="integer",
 *                          example=2
 *                 ),
 *             ),
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK"),
 *   @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *)
 */
class ApiCommunityTelegramUserDetachRequest extends ApiRequest
{

    public function rules():array
    {
        return [
            'telegram_id'=>'required|integer|min:0|exists:telegram_users_community,telegram_user_id',
            'community_id'=>'required|integer|min:0|exists:telegram_users_community,community_id'
        ];
    }

    public function messages(): array
    {
        return [
            'telegram_id.required'=>$this->localizeValidation('telegram_user.required_telegram_id'),
            'telegram_id.integer'=>$this->localizeValidation('telegram_user.integer_telegram_id'),
            'telegram_id.exists'=>$this->localizeValidation('telegram_user.exists_telegram_id'),
            'community_id.required'=>$this->localizeValidation('community.id_required'),
            'community_id.integer'=>$this->localizeValidation('community.id_integer'),
            'community_id.exists'=>$this->localizeValidation('community.id_exists'),
        ];
    }
}
