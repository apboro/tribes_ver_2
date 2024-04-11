<?php

namespace App\Http\ApiRequests\Product;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\GET(
 *  path="/public/product-history/{shop_id}",
 *  operationId="product-public-history-list",
 *  summary="Show products history",
 *  security={{"sanctum": {} }},
 *  tags={"Product history"},
 *     @OA\Parameter(name="shop_id",in="path",
 *         description="Id of shop in database",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *    @OA\Parameter(name="telegram_id",in="query",
 *         description="Users telegram_id",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiProductHistoryListRequest extends ApiRequest
{

    public function all($keys = null)
    {
        return [
                'shop_id' => $this->route('shopId'),
                ] + parent::all();
    }

    public function rules(): array
    {
        return [
            'shop_id'     => 'required|integer|exists:shops,id',
            'telegram_id' => 'required|integer'
        ];
    }
}