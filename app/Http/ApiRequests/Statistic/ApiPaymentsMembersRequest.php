<?php

namespace App\Http\ApiRequests\Statistic;

use App\Http\ApiRequests\ApiRequest;


/**
 * @OA\Get(
 *  path="/api/v3/statistic/payments-list",
 *  operationId="statistic-payments-list",
 *  summary="Show statistic list of payments of Auth user",
 *  security={{"sanctum": {} }},
 *  tags={"Statistic Payments"},
 *  @OA\Parameter(name="offset",in="query",required=false,@OA\Schema(type="integer",)),
 *  @OA\Parameter(name="limit",in="query",required=false,@OA\Schema(type="integer",)),
 *  @OA\Parameter(name="period",in="query",description="Select period",required=false,@OA\Schema(type="string",)),
 *  @OA\Parameter(name="community_id",in="query",description="Community ID",required=false, @OA\Schema(type="integer")),
 *  @OA\Parameter(name="sort_field",in="query", description="Sort field (buy_date, amount)",required=false, @OA\Schema(type="string")),
 *  @OA\Parameter(name="sort_direction",in="query",description="Sort direction (asc, desc)",required=false, @OA\Schema(type="string")),
 *  @OA\Parameter(name="search_field",in="query",description="Search field (type, payable_title, name, username, email)",required=false, @OA\Schema(type="string")),
 *  @OA\Parameter(name="search_query",in="query",description="Search query",required=false, @OA\Schema(type="string")),
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
            'sort_field' => 'string|in:buy_date,amount',
            'sort_direction' =>'string|in:asc,desc',
            'search_field' =>'string|in:type,payable_title,name,username,email',
            'search_query' =>'string'
        ];
    }

    protected function passedValidation(): void
    {
        $this->merge([
            'filter'=> [
                'community_id' => $this->request->get('community_id'),
                'offset' => $this->request->get('offset'),
                'limit' => $this->request->get('limit'),
                'search_field' => $this->request->get('search_field'),
                'search_query' => $this->request->get('search_query'),
                'period' => $this->request->get('period'),
                'sort'=>[
                    'name'=> $this->request->get('sort_field'),
                    'rule' => $this->request->get('sort_direction')
                ]
            ]
        ]);
    }

}