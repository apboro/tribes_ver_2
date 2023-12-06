<?php

namespace App\Http\ApiRequests\Product;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\GET(
 *  path="/api/v3/product/{uuid}",
 *  operationId="product-show-by-uuid",
 *  summary="Show product by uuid",
 *  security={{"sanctum": {} }},
 *  tags={"Product"},
 *     @OA\Parameter(name="uuid",in="path",
 *         description="Uuid of product in database",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiProductShowByUUIDRequest extends ApiRequest
{

    public function all($keys = null)
    {
        return parent::all() + ['uuid' => $this->route('uuid')];
    }

    public function rules(): array
    {
        return [
            'uuid' => 'required|uuid|exists:products,uuid'
        ];
    }
}
