<?php

namespace App\Http\ApiRequests\Market;

use App\Domain\Entity\Shop\DTO\ShopCartDTO;
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
 *      @OA\Parameter(name="options",in="query",description="product options",required=false,@OA\Schema(type="array",)),
 * @OA\Response(response=200, description="OK")
 * )
 */
class ShopCardUpdateRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            ShopCartDTO::INPUT_TELEGRAM_USER_ID => 'required|integer',
            ShopCartDTO::INPUT_SHOP_ID          => 'required|integer|exists:shops,id',
            ShopCartDTO::INPUT_PRODUCT_ID       => 'required|integer|exists:products,id',
            ShopCartDTO::INPUT_QUANTITY         => 'required|integer|min:1',
            ShopCartDTO::INPUT_OPTIONS          => 'nullable|array',
        ];
    }

    /**
     * @throws \JsonException
     */
    public function getCardDTO(): ShopCartDTO
    {
        $validated = $this->validated();
        $options = [];

        if (isset($validated[ShopCartDTO::INPUT_OPTIONS])) {
            $options = $validated[ShopCartDTO::INPUT_OPTIONS];// json_encode($validated[ShopCartDTO::INPUT_OPTIONS], JSON_THROW_ON_ERROR);
        }

        return new ShopCartDTO(
            $validated[ShopCartDTO::INPUT_TELEGRAM_USER_ID],
            $validated[ShopCartDTO::INPUT_SHOP_ID],
            $validated[ShopCartDTO::INPUT_PRODUCT_ID],
            $validated[ShopCartDTO::INPUT_QUANTITY],
            $options,
        );
    }
}