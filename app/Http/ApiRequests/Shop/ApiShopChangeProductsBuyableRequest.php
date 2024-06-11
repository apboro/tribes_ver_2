<?php

namespace App\Http\ApiRequests\Shop;

use App\Http\ApiRequests\ApiRequest;
use App\Rules\UserHasShopRule;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 * path="/api/v3/shops/products/buyable/{id}",
 * operationId="change_buyable_products",
 * summary= "Change buyable products",
 * security= {{"sanctum": {} }},
 * tags= {"Products"},
 *     @OA\Parameter(name="id",in="path",
 *         description="ID of shop in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\Parameter(name="buyable",in="query",
 *         description="Buyable: 0 or 1",
 *         required=true,
 *         @OA\Schema(
 *             type="boolean",
 *             format="int64",
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiShopChangeProductsBuyableRequest extends ApiRequest
{
    public function all($keys = null)
    {
        return [
            'shopId' => $this->route('id'),
            ] + parent::all();
    }

    public function rules(): array
    {
        return ['buyable' => ['required', 'boolean'],
                'shopId' => ['required', 'integer', new UserHasShopRule]];
    }
}