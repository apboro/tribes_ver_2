<?php

namespace App\Http\ApiRequests\Product;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * @OA\Post(
 *  path="/api/v3/products/image",
 *  operationId="product-image-add",
 *  summary="Add image at product",
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
 *                 @OA\Property(property="image",type="file"),
 *                 ),
 *             ),
 *         ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiProductStoreImageRequest extends ApiRequest
{

    public function all($keys = null)
    {
        return ['id' => $this->route('id'),
                'userId' => Auth::user()->id ?? null,
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
            'image' => 'required|image|max:10240|mimes:jpg,jpeg,png,gif,webp',
        ];
    }

}
