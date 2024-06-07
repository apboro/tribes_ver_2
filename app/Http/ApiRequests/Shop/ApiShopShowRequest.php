<?php

namespace App\Http\ApiRequests\Shop;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/v3/shop/{id}",
 *     operationId="show-shop-fields",
 *     summary= "Show shop",
 *     security= {{"sanctum": {} }},
 *     tags= {"Shop"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of shop in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=403, description="Shop is unavailable"),
 * )
 *  * @OA\Get(
 *     path="/api/v3/show/seller_connect/{id}",
 *     operationId="show-seller-connect",
 *     summary= "Show link for seller connect",
 *     security= {{"sanctum": {} }},
 *     tags= {"Shop"},
 *     @OA\Parameter(name="id", in="path", description="ID of shop in database",
 *         required=true, @OA\Schema(type="integer", format="int64",)),
 *     @OA\Response(response=200, description="OK"),
 * )
 */
class ApiShopShowRequest extends ApiRequest
{

    public function all($keys = null)
    {
        return [
                'id' => $this->route('id'),
                ] + parent::all();
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:shops,id',
        ];
    }

}
