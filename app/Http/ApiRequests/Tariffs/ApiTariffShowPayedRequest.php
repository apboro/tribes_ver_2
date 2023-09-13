<?php

namespace App\Http\ApiRequests\Tariffs;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(
 *     path="/api/v3/show/tariff_payed",
 *     operationId="get-payed-tariff-by-hash",
 *     summary="Get payed tariff by hash (with user info)",
 *     security={{"sanctum": {} }},
 *     tags={"Tariffs"},
 *     @OA\Parameter(
 *         name="paymentId",
 *         in="query",
 *         description="ID (hash) of payment in the database",
 *         required=true,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="hash",
 *         in="query",
 *         description="Hash of tariff in the database",
 *         required=true,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiTariffShowPayedRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'hash' => 'required',
            'paymentId' => 'required'
        ];
    }


}