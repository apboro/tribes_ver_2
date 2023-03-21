<?php

namespace App\Http\ApiRequests\Community;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Post(
 *  path="/api/v3/user/community-users/detach_all",
 *  operationId="community-users-detach_all",
 *  summary="Detach user from all communities of user",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Users"},
 *   @OA\RequestBody(
 *     @OA\JsonContent(
 *       @OA\Property(property="telegram_id", type="integer", example=345),
 *     )
 *   ),
 *      @OA\Response(response=200, description="OK")
 *)
 */
class ApiCommunityTelegramUserDetachAllRequest extends ApiRequest
{

    public function rules():array
    {
        return [
            'telegram_id'=>'required|integer|min:0|exists:telegram_users,telegram_id',
        ];
    }

    public function messages(): array
    {
        return [
            'telegram_id.required'=>$this->localizeValidation('telegram_user.required_telegram_id'),
            'telegram_id.integer'=>$this->localizeValidation('telegram_user.integer_telegram_id'),
            'telegram_id.exists'=>$this->localizeValidation('telegram_user.exists_telegram_id'),
        ];
    }
}
