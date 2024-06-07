<?php

namespace App\Http\ApiRequests\Product;

use App\Http\ApiRequests\ApiRequest;


/**
 * @OA\GET(
 *  path="/api/v3/public/products/{shop_id}",
 *  operationId="public-product-list",
 *  summary="Public List product",
 *  security={{"sanctum": {} }},
 *  tags={"Product"},
 *     @OA\Parameter(name="title",in="query",description="Search by product",required=false,@OA\Schema(type="string",)),
 *     @OA\Parameter(name="category_id",in="query",description="Category id",required=false,@OA\Schema(type="string",)),
 *     @OA\Parameter(name="products_in_category",in="query",description="How much products will be show in category",required=false,@OA\Schema(type="integer",)),
 *     @OA\Parameter(name="offset",in="query",description="Begin records from number {offset}",required=false,@OA\Schema(type="integer",)),
 *     @OA\Parameter(name="limit",in="query",description="Total records to display",required=false,@OA\Schema(type="integer",)),
 *   @OA\Response(response=200, description="OK"),
 *   @OA\Response(response=403, description="Shop is unavailable")
 *)
 */
class ApiProductPublicListRequest extends ApiRequest
{

    public function all($keys = null)
    {
        return [
                'shop_id' => $this->route('shopId'),
                ] + parent::all();
    }

    public function rules(): array
    {
        return [
            'shop_id'     => 'required|integer|exists:shops,id',
            'limit'       => 'nullable|integer',
            'offset'      => 'nullable|integer',
            'title'       => 'nullable|string',
            'category_id' => 'nullable|integer',
            'products_in_category' => 'nullable|integer',
        ];
    }
}