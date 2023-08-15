<?php

namespace App\Http\ApiRequests\Tariffs;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(
 *     path="/api/v3/tariffs",
 *     operationId="get-tariffs-list",
 *     summary="Get list of users tariffs",
 *     security={{"sanctum": {} }},
 *     tags={"Tariffs"},
 *     @OA\Parameter(name="offset",in="query",description="Begin records from number {offset}",required=false,@OA\Schema(type="integer",)),
 *     @OA\Parameter(name="limit",in="query",description="Total records to display",required=false,@OA\Schema(type="integer",)),
 *     @OA\Parameter(name="sort_field",in="query",description="SortBy (price, followers)",required=false,@OA\Schema(type="string")),
 *     @OA\Parameter(name="sort_direction",in="query",description="sort_type(asc, desc)",required=false,@OA\Schema(type="string")),
 *     @OA\Parameter(name="community_title",in="query",description="search by community title", required=false,@OA\Schema(type="string")),
 *     @OA\Parameter(name="tariff_title",in="query",description="search by tariff title", required=false,@OA\Schema(type="string")),
 *     @OA\Parameter(name="tariff_is_payable",in="query",description="search by status (tariff payable)", required=false,@OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiTariffListRequest extends ApiRequest
{


}