<?php
declare(strict_types=1);

namespace App\Http\ApiResponses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiResponseServerError extends ApiResponse
{
    protected int $statusCode = self::CODE_SERVER_ERROR;

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
