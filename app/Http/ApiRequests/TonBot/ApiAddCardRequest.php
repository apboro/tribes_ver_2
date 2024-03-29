<?php

namespace App\Http\ApiRequests\TonBot;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Post(
 *  path="/api/v3/tonbot/addCard",
 *  operationId="tonbot-addCard",
 *  summary="tonbot addCard",
 *  security={{"sanctum": {} }},
 *  tags={"Tonbot"},
 *     @OA\Parameter(name="telegram_id",in="query",
 *         description="ID of telegram user",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(name="phone",in="query",
 *         description="phone of telegram user",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiAddCardRequest extends ApiRequest
{
     public function rules(): array
    {
        return [
            'telegram_id' => 'required|integer',
            'phone' => 'required|integer',
        ];
    }
}