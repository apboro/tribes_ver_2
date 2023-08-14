<?php

namespace App\Http\ApiRequests\Payment;

use App\Http\ApiRequests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;
/**
 * @OA\Get(path="/api/v3/payout/cardandaccumulation",
 *     tags={"Payout"},
 *     summary="Get list of cards and accumulations for payout",
 *     operationId="cardandaccumulation",
 *     security={{"sanctum": {} }},
 *
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class CardAndAccumulationForPayoutRequest extends ApiRequest
{

}
