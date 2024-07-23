<?php

namespace App\Http\ApiRequests\Product;

use App\Http\ApiRequests\ApiRequest;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Rules\PriceRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *  path="/api/v3/products",
 *  operationId="product-add",
 *  summary="Add product",
 *  security={{"sanctum": {} }},
 *  tags={"Product"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(property="shop_id",type="integer"),
 *                 @OA\Property(property="title",type="string"),
 *                 @OA\Property(property="description",type="string"),
 *                 @OA\Property(property="price",type="integer"),
 *                 @OA\Property(property="images",type="object", description="Array of images"),
 *                 @OA\Property(property="buyable",type="string"),
 *                 @OA\Property(property="type",type="string", example="product, link"),
 *                 @OA\Property(property="link",type="string", description="URL for type=link"),
 *                 @OA\Property(property="category_id",type="integer"),
 *                 @OA\Property(property="status",type="integer", example="active 1 , disabled 2, archived 3"),
 *                 ),
 *             ),
 *         ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiProductStoreRequest extends ApiRequest
{
    public function prepareForValidation(): void
    {
        $data = $this->all();
        $data['category_id'] = $data['category_id'] ?? 0;            
        $data['type'] = $this->input('type',  Product::TYPES['default']);
        $this->replace($data);
    }

    public function all($keys = null)
    {
        return ['userId' => Auth::user()->id ?? null] + parent::all();
    }

    public function rules(): array
    {
        return [
            'userId'      => 'required|integer',
            'shop_id'     => [
                'required',
                'integer',
                Rule::exists('shops', 'id')->where(function ($query) {
                    return $query->where('user_id', Auth::user()->id ?? null);
                }),
            ],
            'title'       => 'required|string|min:1',
            'description' => 'nullable|string',
            'images'      => 'array',
            'images.*'    => 'image|mimes:jpg,jpeg,png,gif,webp',
            'price'       => ['required', new PriceRule],
            'buyable'     => 'string|in:true,false',
            'category_id' => [
                'integer',
                function ($attribute, $value, $fail) {
                    if ($value != 0 && ProductCategory::isBelongsShop($value, $this->shop_id) === false) {
                        return $fail('Категория не существует.');
                    }
                },
            ],
            'status'      => 'nullable|int',
            'type'        => ['required', Rule::in(Product::TYPES)],
            'link'        => 'nullable|string|url',
        ];
    }

    public function messages(): array
    {
        return [
            'price.*' => 'Значение поля "Цена" должно быть целым числом.'
        ];
    }
}
