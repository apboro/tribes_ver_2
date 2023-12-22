<?php

namespace App\Http\ApiRequests\Product;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;
use Illuminate\Support\Facades\Auth;

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
 *                 @OA\Property(property="title",type="string"),
 *                 @OA\Property(property="description",type="string"),
 *                 @OA\Property(property="price",type="integer"),
 *                 @OA\Property(property="image",type="file"),
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
        return ['authorId' => Auth::user()->author->id ?? null] + parent::all();
    }

    public function rules(): array
    {
        return [
            'authorId' => 'required|integer',
            'title' => 'required|string|min:1',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
            'price' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'price.*' => 'Значение поля "Цена" должно быть целым числом.'
        ];
    }
}
