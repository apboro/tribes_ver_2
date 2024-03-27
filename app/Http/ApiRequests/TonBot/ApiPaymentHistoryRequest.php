<?php

namespace App\Http\ApiRequests\TonBot;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Post(
 *  path="/api/v3/tonbot/payment-history",
 *  operationId="tonbot-payment-history",
 *  summary="tonbot payment history",
 *  security={{"sanctum": {} }},
 *  tags={"Tonbot"},
 *     @OA\Parameter(name="telegram_receiver_id",in="query",
 *         description="ID of telegram receiver", required=false, @OA\Schema(type="integer")
 *     ),
 *    @OA\Parameter(name="telegram_sender_id",in="query",
 *         description="ID of telegram sender", required=false, @OA\Schema(type="integer")
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiPaymentHistoryRequest extends ApiRequest
{
     public function rules(): array
    {
        return [
            'telegram_receiver_id' => 'nullable|integer',
            'telegram_sender_id' => 'nullable|integer'
        ];
    }
}