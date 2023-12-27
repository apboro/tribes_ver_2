<?php

namespace App\Http\ApiRequests\Product;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * @OA\Delete(
 *  path="/api/v3/products/image",
 *  operationId="product-image-remove",
 *  summary="Remove image from product",
 *  security={{"sanctum": {} }},
 *  tags={"Product"},
 *  *     @OA\Parameter(name="id",in="path",
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
 *                 @OA\Property(property="image_id",type="integer"),
 *                 ),
 *             ),
 *         ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiProductRemoveImageRequest extends ApiRequest
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
            'image_id' => 'required|integer',
        ];
    }

}
