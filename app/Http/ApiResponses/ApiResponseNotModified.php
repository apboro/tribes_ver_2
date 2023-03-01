<?php
declare(strict_types=1);

namespace App\Http\ApiResponses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(schema="api_response_not_modified",
 *     @OA\Property(property="message", type="string", nullable=true),
 *     @OA\Property(property="payload", type="array", @OA\Items(), example={}),
 * )
 */
class ApiResponseNotModified extends ApiResponse
{
    protected int $statusCode = self::CODE_NOT_MODIFIED;

    /**
     * Get response.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        return response()->json(null, self::CODE_NOT_MODIFIED, $this->getHeaders());
    }
}
