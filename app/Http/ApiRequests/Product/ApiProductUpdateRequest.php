<?php

namespace App\Http\ApiRequests\Product;

use App\Http\ApiRequests\ApiRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * @OA\Post(
 *  path="/api/v3/products/{id}",
 *  operationId="product-edit",
 *  summary="Edit product",
 *  security={{"sanctum": {} }},
 *  tags={"Product"},
 *     @OA\Parameter(name="id",in="path",
 *         description="ID of product in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(property="title",type="string"),
 *                 @OA\Property(property="description",type="string"),
 *                 @OA\Property(property="price",type="integer"),
 *                 @OA\Property(property="buyable",type="string"),
 *                 @OA\Property(property="category_id",type="integer"),
 *                 ),
 *             ),
 *         ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiProductUpdateRequest extends ApiRequest
{
    public function prepareForValidation(): void
    {
        $data = $this->all();
        $data['category_id'] = $data['category_id'] ?? 0;            
        $this->replace($data);
    }

    public function all($keys = null)
    {
        return ['id' => $this->route('id'),
                'userId' => Auth::user()->id ?? null
                ] + parent::all();
    }

    public function rules(): array
    {
        return [
            'userId' => 'required|integer',
            'id' => [
                'required', 'integer',
                Rule::exists('products')->where(function ($query) {
                    return $query->whereIn('shop_id', Auth::user()->findShopsIds() ?? []);
                }),
            ],
            'title' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:1',
            'buyable' => 'string|in:true,false',
            'category_id' => [
                'integer',
                function ($attribute, $value, $fail) {
                    $product = Product::find($this->id);
                    if (!$product || $product->canMoveToCategory($value) === false) {
                        return $fail('Категория не существует.');
                    }
                },
            ],
        ];
    }
}
