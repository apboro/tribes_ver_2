<?php

namespace App\Http\ApiRequests\Community;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Post(
 *     path="/api/v3/create_chat/check",
 *     operationId="create-chat-check",
 *     summary="Create chat check status",
 *     tags={"Chats"},
 *
 *     @OA\RequestBody(
 *          @OA\JsonContent(
 *               @OA\Property(property="platform", type="string", example="Telegram"),
 *               @OA\Property(property="telegram_user_id", type="integer", example="123456789123"),
 *         )
 *      ),
 *
 *     @OA\Response(response=200, description="OK"),
 * )
 */
class ApiTelegramConnectionSearchRequest extends ApiRequest
{
    public function rules():array
    {
        return [
            'platform'=>'required',
            'telegram_user_id'=>'required'
        ];
    }

}
