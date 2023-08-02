<?php

namespace App\Http\ApiRequests\Statistic;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(
 *  path="/api/v3/statistic/payments-all-time",
 *  operationId="statistic-payments-all-time",
 *  summary="Finance statistic: tariff, donate, course",
 *  security={{"sanctum": {} }},
 *  tags={"Statistic Finance"},
 *  @OA\Response(response=200, description="OK"),
 *  @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *)
 */
class ApiPaymentsSummAllTimeRequest extends ApiRequest
{



}