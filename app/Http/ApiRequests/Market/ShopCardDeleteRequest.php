<?php

namespace App\Http\ApiRequests\Market;

use App\Http\ApiRequests\ApiRequest;
use App\Models\Product;
use OpenApi\Annotations as OA;

/**
 * @OA\Delete(
 *  path="/api/v3/market/card/delete",
 *  operationId="market-card-product-delete",
 *  summary="Card product delete",
 *  security={{"sanctum": {} }},
 *  tags={"Market"},
 *     @OA\Parameter(name="telegram_user_id", in="query", description="telegram user id",required=true,@OA\Schema(type="string",)),
 *     @OA\Parameter(name="shop_card_id",in="query",description="shop card id",required=true,@OA\Schema(type="integer",)),
 *     @OA\Parameter(name="shop_id",in="query",description="shop id",required=true,@OA\Schema(type="integer",)),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ShopCardDeleteRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'telegram_user_id' => 'required|string',
            'id'               => 'required|integer|exists:shop_cards,id',
            'shop_id'          => 'required|integer|exists:shops,id',
        ];
    }
}