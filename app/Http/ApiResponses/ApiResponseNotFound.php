<?php
declare(strict_types=1);

namespace App\Http\ApiResponses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(schema="api_response_not_found",
 *     @OA\Property(property="message", type="string", nullable=true),
 *     @OA\Property(property="payload", type="array", @OA\Items(), example={}),
 * )
 */
class ApiResponseNotFound extends ApiResponse
{
    protected int $statusCode = self::CODE_NOT_FOUND;

    /**
     * Get response.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'code' => $this->statusCode,
            'message' => $this->message ?? null,
        ], $this->statusCode, $this->getHeaders());
    }
}
