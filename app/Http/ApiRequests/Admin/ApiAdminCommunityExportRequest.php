<?php

namespace App\Http\ApiRequests\Admin;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(path="/api/v3/manager/export/communities",
 *     tags={"Admin chats"},
 *     summary="Export chats",
 *     operationId="chats-export",
 *     security={{"sanctum": {} }},
 *     @OA\Parameter(
 *         name="type",
 *         in="path",
 *         description="type of output format",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *             example="csv"
 *         )
 *     ),
 *
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiAdminCommunityExportRequest extends ApiRequest
{
    public function rules():array
    {
        return [
            'type'=>'string'
        ];
    }

    public function messages():array
    {
        return [
            'type.string' => $this->localizeValidation('export.type_string'),
        ];
    }
}
