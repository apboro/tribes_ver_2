<?php
declare(strict_types=1);

namespace App\Http\ApiResponses;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiResponseCommon extends ApiResponse
{
    protected int $statusCode = self::CODE_OK;

    /** @var array|Arrayable  */
    protected $data;

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
            'data' => $this->data ?? [],
            'message' => $this->message ?? null,
            'payload' => $this->payload ?? [],
        ], $this->statusCode, $this->getHeaders());
    }

    /**
     * Set response data.
     *
     * @param array|Arrayable $data
     *
     * @return  $this
     */
    public function data($data): self
    {
        $this->data = $data;

        return $this;
    }
}
