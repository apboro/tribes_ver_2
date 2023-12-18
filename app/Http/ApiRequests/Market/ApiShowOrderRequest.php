<?php

namespace App\Http\ApiRequests\Market;

use App\Http\ApiRequests\ApiRequest;
use App\Models\Product;

/**
 * @OA\GET(
 *  path="/api/v3/market/show/order/{id}",
 *  operationId="market-show-order",
 *  summary="Show order",
 *  security={{"sanctum": {} }},
 *  tags={"Market"},
 *     @OA\Parameter(name="id", in="path", description="order id",required=true,@OA\Schema(type="int",format="int64",)),
 *   @OA\Response(response=200, description="OK")
 *   @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *)
 */
class ApiShowOrderRequest extends ApiRequest
{
    public function all($keys = null)
    {
        $data = parent::all();
        $data['id'] = $this->route('id');

        return $data;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|min:1|exists:shop_orders',
        ];
    }
}