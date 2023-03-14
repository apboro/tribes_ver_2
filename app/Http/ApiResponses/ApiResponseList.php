<?php
declare(strict_types=1);

namespace App\Http\ApiResponses;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ApiResponseList extends ApiResponse
{
    protected int $statusCode = self::CODE_OK;

    /** @var array|Collection|Arrayable */
    protected $list;

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
            'data' => $this->list ?? [],
        ], $this->statusCode, $this->getHeaders());
    }

    /**
     * List items.
     *
     * @param array|Collection|Arrayable $list
     *
     * @return  $this
     */
    public function items($list): ApiResponseList
    {
        $this->list = $list;

        return $this;
    }
}
