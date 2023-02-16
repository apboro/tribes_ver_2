<?php
declare(strict_types=1);

namespace App\Http\ApiResponses;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiResponseValidationError extends ApiResponse
{
    protected int $statusCode = self::CODE_VALIDATION_ERROR;

    /** @var array|Arrayable Validation errors from validator error bag */
    protected array $errors;

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
            'errors' => $this->errors ?? [],
            'message' => $this->message ?? null,
            'payload' => $this->payload ?? [],
        ], $this->statusCode, $this->getHeaders());
    }

    /**
     * Set validation errors.
     *
     * @param array $errors
     *
     * @return  $this
     */
    public function errors(array $errors): self
    {
        $this->errors = $errors;

        return $this;
    }
}
