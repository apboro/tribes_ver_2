<?php

namespace App\Http\ApiRequests\Product;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * @OA\GET(
 *  path="/api/v3/products",
 *  operationId="product-list",
 *  summary="List product",
 *  security={{"sanctum": {} }},
 *  tags={"Product"},
 *     @OA\Parameter(name="shop_id",in="query",description="Shop id",required=true,@OA\Schema(type="string",)),
 *     @OA\Parameter(name="category_id",in="query",description="Category id",required=false,@OA\Schema(type="string",)),
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
        return ['userId' => Auth::user()->id ?? null] + parent::all();
    }

    public function rules(): array
    {
        return ['userId' => 'required|integer',
            'shop_id' => [
                'required', 'integer',
                 Rule::exists('shops', 'id')->where(function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                }),
            ],
            'limit' => 'nullable|integer',
            'offset' => 'nullable|integer',
            'title' => 'nullable|string',
            'category_id' => 'nullable|integer',
            'status.*' => 'nullable|integer',
        ];
    }

    public function getVisibleProductsStatusList(): array
    {
        return $this->input('status', []);
    }
}
