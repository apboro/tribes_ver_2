<?php
declare(strict_types=1);

namespace App\Http\ApiResponses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
