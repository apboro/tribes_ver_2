<?php

namespace App\Http\ApiRequests\Product;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\GET(
 *  path="/api/v3/products",
 *  operationId="product-list",
 *  summary="List product",
 *  security={{"sanctum": {} }},
 *  tags={"Product"},
 *     @OA\Parameter(name="title",in="query",description="Search by product",required=false,@OA\Schema(type="string",)),
 *     @OA\Parameter(name="offset",in="query",description="Begin records from number {offset}",required=false,@OA\Schema(type="integer",)),
 *     @OA\Parameter(name="limit",in="query",description="Total records to display",required=false,@OA\Schema(type="integer",)),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiProductListRequest extends ApiRequest
{

    public function all($keys = null)
    {
        return parent::all() + ['authorId' => Auth::user()->author->id ?? null];
    }

    public function rules(): array
    {
        return ['authorId' => 'required|integer',
            'limit' => 'nullable|integer',
            'offset' => 'nullable|integer',
            'title' => 'nullable|string',
        ];
    }
}
