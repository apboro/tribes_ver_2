<?php

namespace App\Http\ApiRequests\Product;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * @OA\DELETE(
 *  path="/api/v3/products/{id}",
 *  operationId="product-delete",
 *  summary="Delete product",
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
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiProductDeleteRequest extends ApiRequest
{

    public function all($keys = null)
    {
        return parent::all() + [
                'id' => $this->route('id'),
                'authorId' => Auth::user()->author->id ?? null
            ];
    }

    public function rules(): array
    {
        return [
            'authorId' => 'required|integer',
            'id' => [
                'required', 'integer',
                Rule::exists('products')->where(function ($query) {
                    return $query->where('author_id', Auth::user()->author->id);
                }),
            ],
        ];
    }





}
