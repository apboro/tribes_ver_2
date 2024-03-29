<?php

namespace App\Http\ApiRequests\TonBot;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Post(
 *  path="/api/v3/tonbot/payment",
 *  operationId="tonbot-payment",
 *  summary="tonbot payment",
 *  security={{"sanctum": {} }},
 *  tags={"Tonbot"},
 *     @OA\Parameter(name="telegram_receiver_id",in="query",
 *         description="ID of telegram receiver", required=true, @OA\Schema(type="integer")
 *     ),
 *    @OA\Parameter(name="telegram_sender_id",in="query",
 *         description="ID of telegram sender", required=true, @OA\Schema(type="integer")
 *     ),
 *    @OA\Parameter(name="amount",in="query",
 *         description="amount", required=true, @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(name="success_url",in="query",
 *         description="url for redirect", required=false, @OA\Schema(type="string")
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiPaymentRequest extends ApiRequest
{
     public function rules(): array
    {
        return [
            'telegram_receiver_id' => 'required|integer',
            'telegram_sender_id' => 'required|integer',
            'amount' => 'required|integer',
            'success_url' => 'nullable'
        ];
    }
}