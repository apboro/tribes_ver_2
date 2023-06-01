<?php

namespace App\Http\ApiRequests\Statistic;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *  path="/api/v3/statistic/messages/users",
 *  operationId="statistic-message-member-count",
 *  summary="Statistic member messages",
 *  security={{"sanctum": {} }},
 *  tags={"Statistic Message"},
 *  @OA\Parameter(name="offset", in="query",description="Begin records from number {offset}",required=false, @OA\Schema(type="integer",)),
 *  @OA\Parameter(name="limit",in="query",description="Total records to display",required=false,@OA\Schema(type="integer",)),
 *  @OA\Parameter(name="community_ids[]",in="query",description="Community Array",required=false,@OA\Schema(type="array",@OA\Items(type="integer"),)
 * ),
 * @OA\Response(response=200, description="OK"),
 * @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *)
 */
class ApiMessageUserStatisticRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'community_ids' => 'array',
            'community_ids.*' => 'integer',
        ];
    }
}

