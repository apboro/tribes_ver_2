<?php

namespace App\Http\ApiRequests;


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
 *               @OA\Property(property="hash", type="string", example="61e0d7be43532c90768a6c0227f279a5"),
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
            'hash'=>'required'
        ];
    }

    public function messages(): array
    {
        return [
            'hash.required'=>$this->localizeValidation('hash_required')
        ];
    }
}
