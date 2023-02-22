<?php
declare(strict_types=1);

namespace App\Http\ApiResponses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(schema="api_response_forbidden",
 *     @OA\Property(property="message", type="string", nullable=true),
 *     @OA\Property(property="payload", type="array", @OA\Items(), example={}),
 * )
 */
class ApiResponseForbidden extends ApiResponse
{
    protected int $statusCode = self::CODE_FORBIDDEN;

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
            'message' => $this->message ?? null,
            'payload' => $this->payload ?? [],
        ], $this->statusCode, $this->getHeaders());
    }
}
