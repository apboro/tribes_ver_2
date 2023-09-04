<?php

namespace App\Http\ApiRequests\Statistic;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(
 *  path="/api/v3/statistic/publications",
 *  operationId="statistic-publications",
 *  summary="Publication statistic",
 *  security={{"sanctum": {} }},
 *  tags={"Publication statistic"},
 *  @OA\Parameter(name="sort",in="query",description="sorting values: asc, desc. Nothing else.",required=false, @OA\Schema(type="string")),
 *  @OA\Parameter(name="period",in="query",description="period values: day, week, month, year. Nothing else.",required=false, @OA\Schema(type="string")),
 *  @OA\Response(response=200, description="OK"),
 *  @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *)
 */
final class ApiPublicationStatisticRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'sort' => 'in:asc,desc',
            'period' => 'in:day,week,month,year'
        ];
    }

}