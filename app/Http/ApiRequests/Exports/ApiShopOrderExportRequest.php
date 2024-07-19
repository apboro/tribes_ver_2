<?php

namespace App\Http\ApiRequests\Exports;

use App\Http\ApiRequests\ApiRequest;
use App\Rules\UserHasShopRule;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/v3/market/orders/export",
 *     operationId="orders_export",
 *     summary="Export orders by shop",
 *     security={{"sanctum": {} }},
 *     tags={"Exports"},
 *
 *     @OA\Parameter(name="shop_id", in="query", description="Shop ID", required=true, @OA\Schema(type="integer")),
 *
 *     @OA\Response(response="200", description="OK"),
 *     @OA\Response(response="401", description="Unauthorized"),
 *     @OA\Response(response="422", description="Unprocessable Content"),
 * )
 */
class ApiShopOrderExportRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'shop_id' => [
                'required',
                'integer',
                new UserHasShopRule,
            ]
        ];
    }
}