<?php

namespace App\Http\ApiRequests\Product;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\GET(
 *  path="/api/v3/public/product/{id}",
 *  operationId="product-public-show-by-id",
 *  summary="Show product by id (public)",
 *  security={{"sanctum": {} }},
 *  tags={"Product"},
 *     @OA\Parameter(name="id",in="path",
 *         description="Id of product in database",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *    @OA\Parameter(name="telegram_id",in="query",
 *         description="Users telegram_id",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiProductPublicShowRequest extends ApiRequest
{

    public function all($keys = null)
    {
        return ['id' => $this->route('id')] + parent::all();
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:products,id',
            'telegram_id' => 'nullable|integer',
        ];
    }
}
