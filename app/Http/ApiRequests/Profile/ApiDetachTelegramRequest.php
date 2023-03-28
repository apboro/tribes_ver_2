<?php

namespace App\Http\ApiRequests\Profile;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Post(
 *  path="/api/v3/user/telegram/detach",
 *  operationId="detach_telegram_account",
 *  summary="Detach Telegram Account",
 *  security={{"sanctum": {} }},
 *  tags={"User Telegram"},
 *  @OA\RequestBody(
 *      @OA\JsonContent(
 *          @OA\Property(property="telegram_id", type="integer"),
 *          example={"telegram_id": 5826257074}
 *      )
 * ),
 *
 *     @OA\Response(response=200, description="Telegram account detached successfully", @OA\JsonContent(
 *         @OA\Property(property="message", type="string", nullable=true),
 *         @OA\Property(property="payload", type="array", @OA\Items(), example={}))
 *      ),
 *      @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/api_response_unauthorized")),
 *      @OA\Response(response=500, description="Server Error", @OA\JsonContent(ref="#/components/schemas/api_response_error")),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *)
 */
class ApiDetachTelegramRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'telegram_id' => 'required|integer',
        ];
    }

}