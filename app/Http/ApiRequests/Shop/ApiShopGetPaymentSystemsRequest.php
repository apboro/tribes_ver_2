<?php

namespace App\Http\ApiRequests\Shop;

use App\Http\ApiRequests\ApiRequest;
use App\Rules\UserHasShopRule;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 * path="/api/v3/shops/get_payment_systems/{id}",
 * operationId="get_payment_system_shops",
 * summary= "Get payment system",
 * security= {{"sanctum": {} }},
 * tags= {"Shop"},
 *     @OA\Parameter(name="id",in="path",
 *         description="ID of shop in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiShopGetPaymentSystemsRequest extends ApiRequest
{
    public function all($keys = null)
    {
        return [
            'shopId' => $this->route('id'),
            ] + parent::all();
    }

    public function rules(): array
    {
        return ['shopId' => ['required', 'integer', new UserHasShopRule]];
    }
}