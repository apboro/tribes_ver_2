<?php

namespace App\Http\ApiRequests\Market;

use App\Http\ApiRequests\ApiRequest;
use App\Models\Product;

/**
 * @OA\GET(
 *  path="/api/v3/market/card/list",
 *  operationId="market-card-list",
 *  summary="Card list",
 *  security={{"sanctum": {} }},
 *  tags={"Market"},
 *     @OA\Parameter(name="telegram_user_id", in="query", description="telegram user id",required=true,@OA\Schema(type="integer",)),
 *     @OA\Parameter(name="shop_id",in="query",description="shop id",required=true,@OA\Schema(type="integer",)),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ShopCardListRequest extends ApiRequest
{
    public function getTgUserId(): string
    {
        return $this->input('telegram_user_id');
    }

    public function getShopId(): int
    {
        return $this->input('shop_id');
    }

    public function rules(): array
    {
        return [
            'telegram_user_id' => 'required|integer',
            'shop_id'          => 'required|integer|exists:shops,id',
        ];
    }
}