<?php

namespace App\Http\ApiRequests\Product;

use App\Http\ApiRequests\ApiRequest;
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
 *                 @OA\Property(property="image",type="file"),
 *                 ),
 *             ),
 *         ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiProductUpdateRequest extends ApiRequest
{
    public function all($keys = null)
    {
        return ['id' => $this->route('id'),
                'authorId' => Auth::user()->author->id ?? null
                ] + parent::all();
    }

    public function rules(): array
    {
        return [
            'authorId' => 'required|integer',
            'id' => [
                'required', 'integer',
                Rule::exists('products')->where(function ($query) {
                    return $query->where('author_id', Auth::user()->author->id ?? null);
                }),
            ],
            'title' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:10240',
            'price' => 'required|numeric|min:1',
        ];
    }
}
