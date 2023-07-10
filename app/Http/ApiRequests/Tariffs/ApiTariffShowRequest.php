<?php

namespace App\Http\ApiRequests\Tariffs;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(
 *     path="/api/v3/show/tariff",
 *     operationId="get-tariff-with-id-and-hash",
 *     summary="Get tariff by ID and hash",
 *     security={{"sanctum": {} }},
 *     tags={"Tariffs"},
 *     @OA\Parameter(
 *         name="id",
 *         in="query",
 *         description="ID of tariff in the database",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="hash",
 *         in="query",
 *         description="Hash of tariff in the database",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiTariffShowRequest extends ApiRequest
{


}