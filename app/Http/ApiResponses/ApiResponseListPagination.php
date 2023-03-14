<?php
declare(strict_types=1);

namespace App\Http\ApiResponses;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ApiResponseListPagination extends ApiResponse
{
    protected int $statusCode = self::CODE_OK;

    /** @var array|Collection|Arrayable|LengthAwarePaginator  */
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
        $hasPagination = $this->list instanceof LengthAwarePaginator;
        $count = method_exists($this->list, 'count') ? $this->list->count() : count($this->list);

        return response()->json([
            'list' => method_exists($this->list, 'items') ? $this->list->items() : $this->list,
            'pagination' => [
                'current_page' => $hasPagination ? $this->list->currentPage() : 1,
                'last_page' => $hasPagination ? $this->list->lastPage() : 1,
                'from' => $hasPagination ? $this->list->firstItem() : 1,
                'to' => $hasPagination ? $this->list->lastItem() : $count,
                'total' => $hasPagination ? $this->list->total() : $count,
                'per_page' => $hasPagination ? $this->list->perPage() : $count,
            ],
        ], $this->statusCode, $this->getHeaders());
    }

    /**
     * List items.
     *
     * @param array|Collection|Arrayable|LengthAwarePaginator $list
     *
     * @return ApiResponseListPagination
     */
    public function items($list): ApiResponseListPagination
    {
        $this->list = $list;

        return $this;
    }
}
