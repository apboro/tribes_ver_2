<?php

namespace App\Http\ApiRequests\Statistic;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *  path="/api/v3/statistic/moderation/export",
 *  operationId="statistic-moderation-export",
 *  summary="Export statistic",
 *  security={{"sanctum": {} }},
 *  tags={"Statistic Moderation"},
 *  @OA\Parameter(name="export_type",in="query",description="Export format",required=false,@OA\Schema(type="string",)),
 *  @OA\Parameter(name="community_ids[]",in="query",description="Community Array",required=false,@OA\Schema(type="array",@OA\Items(type="integer"))),
 * @OA\Response(response=200, description="OK"),
 * @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *)
 */
class ApiModerationStatisticExportRequest extends FormRequest
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
