<?php
declare(strict_types=1);

namespace App\Http\ApiResponses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiResponseRedirect extends ApiResponse
{
    protected int $statusCode = self::CODE_REDIRECT;

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
