<?php

namespace App\Http\ApiRequests\Statistic;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;


/**
 * @OA\Get(
 *  path="/api/v3/statistic/members/export",
 *  operationId="statistic-member-export",
 *  summary="Export statistic",
 *  security={{"sanctum": {} }},
 *  tags={"Statistic Members"},
 *  @OA\Parameter(name="export_type",in="query",description="Export format (xlsx, csv)",required=false,@OA\Schema(type="string",)),
 *  @OA\Parameter(name="community_ids[]",in="query",description="Community Array",required=false,@OA\Schema(type="array",@OA\Items(type="integer"))),
 * @OA\Response(response=200, description="OK"),
 * @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *)
 */
class ApiMemberStatisticExportRequest extends ApiRequest
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
            'community_ids.*' => 'integer|exists:communities,id',
            'export_type' => 'string|in:xlsx,csv',
        ];
    }
}
