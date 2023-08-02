<?php

namespace App\Http\ApiRequests\Statistic;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Patch(
 *  path="/api/v3/statistic/payments-payouts",
 *  operationId="statistic-payments-payouts",
 *  summary="Show all payouts",
 *  security={{"sanctum": {} }},
 *  tags={"Payouts"},
 *  @OA\Parameter(name="offset",in="query",required=false,@OA\Schema(type="integer",)),
 *  @OA\Parameter(name="limit",in="query",required=false,@OA\Schema(type="integer",)),
 *  @OA\Response(response=200, description="OK"),
 *  @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *)
 */
class ApiPayoutsListRequest extends ApiRequest
{



}