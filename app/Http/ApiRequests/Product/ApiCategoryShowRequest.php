<?php

namespace App\Http\ApiRequests\Product;

use App\Http\ApiRequests\ApiRequest;
use App\Models\ProductCategory;
use Illuminate\Validation\Rule;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/v3/products/category/{id}",
 *     operationId="show-products-category",
 *     summary= "Show products category",
 *     security= {{"sanctum": {} }},
 *     tags= {"Product categories"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of category in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="shop_id",
 *         in="query",
 *         description="Shop ID in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\Response(response=200, description="OK"),
 * )
 */
/**
 * @OA\Get(
 *     path="/api/v3/products/category/",
 *     operationId="show-products-categories",
 *     summary= "Show list of shop categories",
 *     security= {{"sanctum": {} }},
 *     tags= {"Product categories"},
 *     @OA\Parameter(
 *         name="shop_id",
 *         in="query",
 *         description="Shop ID in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *      @OA\Parameter(
 *         name="name",
 *         in="query",
 *         description="Name of category for search",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *      @OA\Parameter(
 *         name="hide_empty",
 *         in="query",
 *         description="Hide empty categories: 0 or 1",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *     @OA\Response(response=200, description="OK"),
 * )
 */

class ApiCategoryShowRequest extends ApiRequest
{

    public function all($keys = null)
    {
        return $this->route('id') ? ['id' => $this->route('id')] + parent::all() : parent::all();
    }

    public function rules(): array
    {
        return [
            'id' => 'nullable|integer|exists:product_categories,id',
            'name' => 'nullable|string',
            'shop_id' => [
                'required',
                'integer',
                'exists:shops,id',
                function ($attribute, $value, $fail) {
                    if ($this->id && ProductCategory::isBelongsShop($this->id, $this->shop_id) === false) {
                        $fail('Категория не принадлежит магазину.');
                    }
                },
            ],
            'hide_empty' => 'nullable|bool'
        ];
    }
}
