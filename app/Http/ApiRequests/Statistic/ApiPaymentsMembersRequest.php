<?php

namespace App\Http\ApiRequests\Statistic;

use App\Http\ApiRequests\ApiRequest;


/**
 * @OA\Get(
 *  path="/api/v3/statistic/payments-list?XDEBUG_SESSION_START=PHPSTORM",
 *  operationId="statistic-payments-list",
 *  summary="Show statistic list of payments of Auth user",
 *  security={{"sanctum": {} }},
 *  tags={"Statistic Payments"},
 *  @OA\Parameter(name="offset",in="query",required=false,@OA\Schema(type="integer",)),
 *  @OA\Parameter(name="limit",in="query",required=false,@OA\Schema(type="integer",)),
 *  @OA\Parameter(name="period",in="query",description="Select period",required=false,@OA\Schema(type="string",)),
 *  @OA\Parameter(name="community_id",in="query",description="Community ID",required=false, @OA\Schema(type="integer")),
 *  @OA\Parameter(name="sort_field",in="query", description="Sort field (date, sum)",required=false, @OA\Schema(type="string")),
 *  @OA\Parameter(name="sort_direction",in="query",description="Sort direction (asc, desc)",required=false, @OA\Schema(type="string")),
 *  @OA\Parameter(name="search",in="query",description="Search field (type, purpose, name, username, email)",required=false, @OA\Schema(type="string")),
 *  @OA\Response(response=200, description="OK"),
 *  @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *)
 */
class ApiPaymentsMembersRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'community_id' => 'integer|exists:communities,id',
            'period' => 'string|in:day,week,month,year',
            'sort_field' => 'string|in:date,sum',
            'sort_direction' =>'string|in:asc,desc',
            'search' =>'string|in:type,purpose,name,username,email'
        ];
    }

    protected function passedValidation(): void
    {
        $this->merge([
            'community_ids' => $this->request->get('community_id'),
            'filter'=> [
                'offset' => $this->request->get('offset'),
                'limit' => $this->request->get('limit'),
                'period' => $this->request->get('period'),
                'sort'=>[
                    'name'=> $this->request->get('sort_name'),
                    'rule' => $this->request->get('sort_direction')
                ]
            ]
        ]);
    }

}