<?php

namespace App\Http\ApiRequests\Admin;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(path="/api/v3/manager/export/users",
 *     tags={"Admin users"},
 *     summary="Export users",
 *     operationId="admin-users-export",
 *     security={{"sanctum": {} }},
 *     @OA\Parameter(
 *         name="type",
 *         in="query",
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
class ApiUserManagerExportRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'type' => 'string|in:xlsx,csv'
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'type' => strtolower($this->request->get('type'))
        ]);
    }

    public function messages(): array
    {
        return [
            'type.string' => $this->localizeValidation('export.type_string'),
        ];
    }
}
