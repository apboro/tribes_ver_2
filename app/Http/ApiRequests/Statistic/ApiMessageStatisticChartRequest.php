<?php

namespace App\Http\ApiRequests\Statistic;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *  path="/api/v3/statistic/messages/charts",
 *  operationId="statistic-message-chart",
 *  summary="Statistic message for chart",
 *  security={{"sanctum": {} }},
 *  tags={"Statistic Message"},
 *  @OA\Parameter(name="period",in="query",description="Select period (day, week, month, year)",required=false,@OA\Schema(type="string",)),
 *  @OA\Parameter(name="community_ids[]",in="query",description="Community Array",required=false,@OA\Schema(type="array",@OA\Items(type="integer"))),
 *  @OA\Parameter(name="telegram_users_id[]",in="query",description="Teelgram ids Array",required=false,@OA\Schema(type="array",@OA\Items(type="integer"))),
 * @OA\Response(response=200, description="OK"),
 * @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *)
 */
class ApiMessageStatisticChartRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'period' => 'string|in:day,week,month,year',
            'community_ids' => 'array',
            'community_ids.*' => 'integer|exists:communities,id',
            'telegram_users_id' => 'array',
            'telegram_users_id.*' => 'integer',
        ];
    }
}
