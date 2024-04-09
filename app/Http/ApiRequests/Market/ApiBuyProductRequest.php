<?php

namespace App\Http\ApiRequests\Market;

use App\Http\ApiRequests\ApiRequest;
use App\Models\Market\ShopDelivery;

/**
 * @OA\POST(
 *  path="/api/v3/market/product/buy",
 *  operationId="market-product-buy",
 *  summary="Buy product",
 *  security={{"sanctum": {} }},
 *  tags={"Market"},
 *     @OA\Parameter(name="telegram_user_id", in="query", description="telegram user id",required=true,@OA\Schema(type="integer",)),
 *     @OA\Parameter(name="product_id_list",in="query",description="product id list",required=true,@OA\Schema(type="object",)),
 *     @OA\Parameter(name="shop_id",in="query",description="shop id",required=true,@OA\Schema(type="integer",)),
 *     @OA\Parameter(name="address",in="query",description="Delivery address",required=true,@OA\Schema(type="string",)),
 *     @OA\Parameter(name="email",in="query",description="Delivery email",required=true,@OA\Schema(type="string",)),
 *     @OA\Parameter(name="phone",in="query",description="Delivery phone",required=false,@OA\Schema(type="string",)),
 *     @OA\Parameter(name="first_name",in="query",description="Telegram first_name",required=false,@OA\Schema(type="string",)),
 *     @OA\Parameter(name="last_name",in="query",description="Telegram last_name",required=false,@OA\Schema(type="string",)),
 *     @OA\Parameter(name="username",in="query",description="Telegram username",required=false,@OA\Schema(type="string",)),
 *     @OA\Parameter(name="platform",in="query",description="Telegram platform",required=false,@OA\Schema(type="boolean",)),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiBuyProductRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'telegram_user_id' => 'required|integer',
            'product_id_list'  => 'required|array',
            'shop_id'          => 'required|integer',
            'address'          => 'nullable|string',
            'email'            => 'nullable|string',
            'phone'            => 'nullable|string',
            'first_name'       => 'nullable|string',
            'last_name'        => 'nullable|string',
            'username'         => 'nullable|string',
            'is_mobile'        => 'boolean',
        ];
    }

    public function getTelegramUserDTO(): array
    {
        return [
            'telegram_user_id' => $this->input('telegram_user_id'),
            'user_name'        => $this->input('username'),
            'last_name'        => $this->input('last_name'),
            'first_name'       => $this->input('first_name'),
        ];
    }

    public function getUserDTO(): array
    {
        return [
            'email' => $this->input('email'),
            'phone' => $this->input('phone'),
        ];
    }

    public function getDeliveryDTO()
    {
        return [
            ShopDelivery::KEY_ADDRESS => $this->input('address'),
            ShopDelivery::KEY_EMAIL   => $this->input('email', ''),
        ];
    }

    public function getProductIdList()
    {
        return $this->input('product_id_list', []);
    }
}