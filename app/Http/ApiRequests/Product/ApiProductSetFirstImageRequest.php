<?php

namespace App\Http\ApiRequests\Product;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * @OA\Post(
 *  path="/api/v3/products/image/{id}",
 *  operationId="product-image-set-first",
 *  summary="Set first image in product",
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
 *                 @OA\Property(property="image_id",type="integer"),
 *                 ),
 *             ),
 *         ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiProductSetFirstImageRequest extends ApiRequest
{
    private function getUserId()
    {
        return Auth::user()->id;
    }

    private function checkProductAndUser()
    {
        return Rule::exists('products')->where(function ($query) {
            return $query->whereIn('shop_id', Auth::user()->findShopsIds() ?? []);
            });
    }

    public function all($keys = null)
    {
        $all = [
            'id'        => $this->route('id'),
            'userId'  => $this->getUserId() ?? null
        ];

        return $all + parent::all();
    }

    public function rules(): array
    {
        return [
            'userId' => 'required|integer',
            'id' => [
                'required', 'integer',
                $this->checkProductAndUser(),
            ],
            'image_id' => 'required|integer',
        ];
    }
}