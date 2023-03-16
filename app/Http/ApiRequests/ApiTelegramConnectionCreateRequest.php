<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Post(
 *     path="/api/v3/create_chat/init",
 *     operationId="create-chat-init",
 *     summary="Create chat initialization",
 *     tags={"Chats"},
 *
 *     @OA\RequestBody(
 *          @OA\JsonContent(
 *               @OA\Property(property="platform", type="string", example="Telegram"),
 *               @OA\Property(property="type", type="string", example="group"),
 *               @OA\Property(property="telegram_id", type="integer", example=154854847),
 *         )
 *      ),
 *     @OA\Response(response=200, description="Connection initialized"),
 * )
 */
class ApiTelegramConnectionCreateRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'platform' => 'required|string',
            'type' => 'required|string',
            'telegram_id' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'platform.required' => $this->localizeValidation('platform.required'),
            'platform.string' => $this->localizeValidation('platform.string'),
            'type.required' => $this->localizeValidation('type.required'),
            'type.string' => $this->localizeValidation('type.string'),
        ];
    }
}
