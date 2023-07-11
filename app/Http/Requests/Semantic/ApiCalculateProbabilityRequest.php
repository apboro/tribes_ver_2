<?php

namespace App\Http\Requests\Semantic;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(
 *  path="/api/v3/statistic/semantic/charts",
 *  operationId="statistic_semantic_charts",
 *  summary="Statistic semantic chart",
 *  security={{"sanctum": {} }},
 *  tags={"Statistic Semantic"},
 *  @OA\Parameter(name="period", in="query", description="Period (day, week, month, year)", required=false, @OA\Schema(type="string")),
 *  @OA\Parameter(name="community_id",in="query",description="Community ID",required=false,@OA\Schema(type="integer")),
 *  @OA\Response(response=200, description="OK"),
 *  @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *)
 */
class ApiCalculateProbabilityRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'period'  => ['string'],
            'community_id' => ['integer']
        ];
    }
}
