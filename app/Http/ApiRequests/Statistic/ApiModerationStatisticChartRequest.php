<?php

namespace App\Http\ApiRequests\Statistic;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *  path="/api/v3/statistic/moderation/charts",
 *  operationId="statistic-moderation-chart",
 *  summary="Show statistic of moderation for chart",
 *  security={{"sanctum": {} }},
 *  tags={"Statistic Moderation"},
 *  @OA\Parameter(name="period",in="query",description="Select period",required=false,@OA\Schema(type="string",)),
 *  @OA\Parameter(name="community_ids[]",in="query",description="Community Array",required=false,@OA\Schema(type="array",@OA\Items(type="integer"))),
 * @OA\Response(response=200, description="OK"),
 * @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *)
 */
class ApiModerationStatisticChartRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'period' => 'string|in:day,week,month,year',
            'community_ids' => 'array',
            'community_ids.*' => 'integer|exists:communities,id',
        ];
    }
}
