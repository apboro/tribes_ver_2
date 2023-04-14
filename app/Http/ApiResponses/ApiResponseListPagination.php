<?php
declare(strict_types=1);

namespace App\Http\ApiResponses;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;

class ApiResponseListPagination extends ApiResponse
{
    protected int $statusCode = self::CODE_OK;

    /** @var array|Collection|Arrayable|LengthAwarePaginator|ResourceCollection  */
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
        $isResource = $this->list instanceof ResourceCollection;
        $hasPagination = $this->list instanceof LengthAwarePaginator ||
            $isResource && ($this->list->resource instanceof AbstractPaginator || $this->list->resource instanceof AbstractCursorPaginator);

        $list = $isResource ? $this->list->resource : $this->list;

        return response()->json([
            'data' => method_exists($list, 'items') ? $list->items() : $list,
        ], $this->statusCode, $this->getHeaders());
    }

    /**
     * List items.
     *
     * @param array|Collection|Arrayable|LengthAwarePaginator|ResourceCollection $list
     *
     * @return ApiResponseListPagination
     */
    public function items($list): ApiResponseListPagination
    {
        $this->list = $list;

        return $this;
    }
}
