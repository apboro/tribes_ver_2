<?php

namespace App\Http\ApiRequests\Product;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
 *                 ),
 *             ),
 *         ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiProductStoreRequest extends ApiRequest
{

    public function all($keys = null)
    {
        return ['userId' => Auth::user()->id ?? null] + parent::all();
    }

    public function rules(): array
    {
        return [
            'userId' => 'required|integer',
            'shop_id' => [
                'required', 'integer',
                Rule::exists('shops', 'id')->where(function ($query) {
                    return $query->where('user_id', Auth::user()->id ?? null);
                }),
            ],
            'title' => 'required|string|min:1',
            'description' => 'nullable|string',
            'images' => 'array',
            'images.*' => 'image|mimes:jpg,jpeg,png,gif,webp',
            'price' => 'required|integer|min:1',
            'buyable' => 'string|in:true,false',
        ];
    }

    public function messages(): array
    {
        return [
            'price.*' => 'Значение поля "Цена" должно быть целым числом.'
        ];
    }
}
