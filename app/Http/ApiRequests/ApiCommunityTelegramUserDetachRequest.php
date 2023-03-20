<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;


/**
 * @OA\Post(
 *  path="/api/v3/user/community-users/detach",
 *  operationId="community-users-detach",
 *  summary="Detach user from community",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Users"},
 *   @OA\RequestBody(
 *     @OA\JsonContent(
 *       @OA\Property(property="telegram_id", type="integer", example=345),
 *       @OA\Property(property="community_id", type="integer", example=2),
 *     )
 *   ),
 *      @OA\Response(response=200, description="OK")
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
