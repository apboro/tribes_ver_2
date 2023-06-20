<?php

namespace App\Http\ApiRequests\Statistic;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(
 *  path="/api/v3/statistic/export-all-data",
 *  operationId="statistic-export-all-data",
 *  summary="Export all statistic",
 *  security={{"sanctum": {} }},
 *  tags={"Statistic"},
 *  @OA\Parameter(name="export_type", in="query",description="",required=false, @OA\Schema(type="string",)),
 *
 * @OA\Response(response=200, description="OK"),
 * @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *)
 */
class ExportAllStatisticData extends ApiRequest
{

    public function rules(): array
    {
        return [
            'community_ids' => 'array',
            'community_ids.*' => 'integer|exists:communities,id',
            'export_type' => 'string|in:xlsx,csv',
        ];
    }
}
