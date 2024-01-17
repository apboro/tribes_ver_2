<?php

namespace App\Http\ApiRequests\Market;

use App\Http\ApiRequests\ApiRequest;
use App\Models\Product;

/**
 * @OA\POST(
 *  path="/api/v3/market/card/update",
 *  operationId="market-card-update",
 *  summary="Card update",
 *  security={{"sanctum": {} }},
 *  tags={"Market"},
 *      @OA\Parameter(name="telegram_user_id", in="query", description="telegram user id",required=true,@OA\Schema(type="integer",)),
 *      @OA\Parameter(name="shop_id",in="query",description="shop id",required=true,@OA\Schema(type="integer",)),
 *      @OA\Parameter(name="product_id",in="query",description="product id",required=true,@OA\Schema(type="integer",)),
 *      @OA\Parameter(name="quantity",in="query",description="product id",required=true,@OA\Schema(type="integer",)),
 * @OA\Response(response=200, description="OK")
 * )
 */
class ShopCardUpdateRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'telegram_user_id' => 'required|integer',
            'shop_id'          => 'required|integer|exists:shops,id',
            'product_id'       => 'required|integer|exists:products,id',
            'quantity'         => 'required|integer|min:1',
        ];
    }
}