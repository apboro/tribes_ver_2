<?php

namespace App\Http\ApiRequests\Product;

use App\Http\ApiRequests\ApiRequest;


/**
 * @OA\GET(
 *  path="/api/v3/public/products/{author}",
 *  operationId="public-product-list",
 *  summary="Public List product",
 *  security={{"sanctum": {} }},
 *  tags={"Product"},
 *     @OA\Parameter(
 *         name="author",
 *         in="path",
 *         description="ID of author of good",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\Parameter(name="title",in="query",description="Search by product",required=false,@OA\Schema(type="string",)),
 *     @OA\Parameter(name="offset",in="query",description="Begin records from number {offset}",required=false,@OA\Schema(type="integer",)),
 *     @OA\Parameter(name="limit",in="query",description="Total records to display",required=false,@OA\Schema(type="integer",)),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiProductPublicListRequest extends ApiRequest
{
    public function all($keys = null)
    {
        return parent::all() + ['authorId' => $this->route('author')];
    }

    public function rules(): array
    {
        return ['authorId' => 'required|integer|exists:authors,id',
            'limit' => 'nullable|integer',
            'offset' => 'nullable|integer',
            'title' => 'nullable|string',
        ];
    }
}