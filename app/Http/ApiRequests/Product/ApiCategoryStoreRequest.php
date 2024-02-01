<?php

namespace App\Http\ApiRequests\Product;

use App\Http\ApiRequests\ApiRequest;
use App\Models\ProductCategory;
use OpenApi\Annotations as OA;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * @OA\Post(
 *  path="/api/v3/products/category",
 *  operationId="product-category-add",
 *  summary="Add category for products",
 *  security={{"sanctum": {} }},
 *  tags={"Product categories"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(property="shop_id",type="integer"),
 *                 @OA\Property(property="name",type="string"),
 *                 @OA\Property(property="parent_id",type="integer"),
 *                 ),
 *             ),
 *         ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiCategoryStoreRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'shop_id' => [
                'required', 'integer',
                Rule::exists('shops', 'id')->where(function ($query) {
                    return $query->where('user_id', Auth::user()->id ?? null);
                }),
            ],
            'name' => 'required|string|min:1',
            'parent_id' => [
                'required', 'integer',
                function ($attribute, $value, $fail) {
                    $shopId = (int) $this->shop_id;
                    if ($value != 0 && ProductCategory::isBelongsShop($value, $shopId) === false) {
                        $fail('parent_id не принадлежит магазину shop_id.');
                    }
                },
            ],
        ];
    }
}
