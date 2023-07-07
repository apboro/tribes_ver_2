<?php

namespace App\Http\ApiRequests\Tariffs;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(
 *     path="/api/v3/tariff/{id}",
 *     operationId="get-tariff-with-id",
 *     summary="Get tariff by ID",
 *     security={{"sanctum": {} }},
 *     tags={"Tariffs"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of tariff in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiTariffShowRequest extends ApiRequest
{


}