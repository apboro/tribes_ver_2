<?php

namespace App\Http\ApiRequests\Product;

use App\Http\ApiRequests\ApiRequest;
use App\Models\Product;
use OpenApi\Annotations as OA;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Get(
 *  path="/api/v3/product/{id}",
 *  operationId="product-show",
 *  summary="Show product by id",
 *  security={{"sanctum": {} }},
 *  tags={"Product"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of product in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 *
 * @OA\Get(
 *   path="/api/v3/product/change/status/{id}",
 *   operationId="product-change-status",
 *   summary="change product status",
 *   security={{"sanctum": {} }},
 *   tags={"Product"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of product in database",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64",
 *          )
 *      ),
 *
 *      @OA\Response(response=200, description="OK"),
 *      @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *  )
 */
class ApiProductShowRequest extends ApiRequest
{
    public function all($keys = null)
    {
        return [
                'id' => $this->route('id'),
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
            'status' => [
                'sometimes',
                'required',
                'integer',
                static function ($attribute, $value, $fail) {
                  if (!isset(Product::STATUS_NAMES_LIST[$value])) {
                      $fail('Неверный статус.');
                  }
                },
            ],
        ];
    }

    public function getProductId()
    {
        return $this->route('id');
    }

    public function getStatusId(): int
    {
        return $this->input('status');
    }
}
