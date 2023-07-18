<?php

namespace App\Http\ApiRequests\Statistic;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *  path="/api/v3/statistic/payments-charts",
 *  operationId="statistic-payments-charts",
 *  summary="Show statistic charts of payments of Auth user",
 *  security={{"sanctum": {} }},
 *  tags={"Statistic Payments"},
 *  @OA\Parameter(name="period",in="query",description="Select period",required=false,@OA\Schema(type="string",)),
 *  @OA\Parameter(name="community_id",in="query",description="Community ID",required=false, @OA\Schema(type="integer")),
 *  @OA\Response(response=200, description="OK"),
 *  @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *)
 */
class ApiPaymentsStatisticRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'community_id' => 'integer|exists:communities,id',
            'period' => 'string|in:day,week,month,year',
        ];
    }

    protected function passedValidation(): void
    {
        $this->merge([
           'community_ids' => $this->request->get('community_id'),
           'filter'=> [
               'period' => $this->request->get('period'),
           ]
        ]);
    }

}