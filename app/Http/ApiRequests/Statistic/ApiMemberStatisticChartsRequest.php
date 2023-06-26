<?php

namespace App\Http\ApiRequests\Statistic;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *  path="/api/v3/statistic/members",
 *  operationId="statistic-members",
 *  summary="Show statistic of telegram user members for chart",
 *  security={{"sanctum": {} }},
 *  tags={"Statistic Members"},
 *  @OA\Parameter(name="period",in="query",description="Select period (day, week, month, year)",required=false,@OA\Schema(type="string")),
 *  @OA\Parameter(name="community_id", in="query", description="Community ID", required=false, @OA\Schema(type="integer")),
 * @OA\Response(response=200, description="OK"),
 * @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *)
 */
class ApiMemberStatisticChartsRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'period' => 'string|in:day,week,month,year',
            'community_id' => 'integer',
            'community_id.*' => 'integer|exists:communities,id',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'community_ids' => $this->request->get('community_id') ? [$this->request->get('community_id')] : null
        ]);
    }
}
