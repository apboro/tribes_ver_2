<?php

namespace App\Http\ApiRequests\Shop;

use App\Http\ApiRequests\ApiRequest;

/**
 *
 * @OA\POST(
 *  path="/api/v3/shop/safe-route", operationId="store-shop-safe-route", summary="store shop-safe-route",
 *  security={{"sanctum": {} }}, tags={"shop safe-route"},
 *     @OA\Parameter(name="shop_id", in="query", description="shop id",required=true,@OA\Schema(type="integer",)),
 *     @OA\Parameter(name="safe_shop_id",in="query",description="integrate safe route shop id",required=true,@OA\Schema(type="integer",)),
 *     @OA\Parameter(name="token",in="query",description="integrate safe route token",required=false,@OA\Schema(type="string",)),
 *
 * @OA\Response(response=200, description="OK")
 * )
 *
 * @OA\Get(path="/api/v3/shop/safe-route", operationId="index-shop-safe-route", summary="index shops safe route integrations",
 *  security={{"sanctum": {} }}, tags={"shop safe-route"},
 *  @OA\Response(response=200, description="OK"),
 *  @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 *
 * @OA\Get(path="/api/v3/shop/safe-route/{id}", operationId="shop safe-route", summary="shop safe-route by shop id",
 *  security={{"sanctum": {} }}, tags={"shop safe-route"},
 *     @OA\Parameter(name="id", in="path", description="ID of shop in database", required=true,
 *         @OA\Schema(type="integer", format="int64",)),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 *
 * @OA\Put(path="/api/v3/shop/safe-route/{id}",
 *     operationId="upodate safe-route", summary="upodate safe-route", security={{"sanctum": {} }}, tags={"shop safe-route"},
 *     @OA\Parameter(name="id", in="path", description="ID of shop in database", required=true,
 *         @OA\Schema(type="integer", format="int64",)),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch",
 *     @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *  )
 *
 * @OA\DELETE(
 *  path="/api/v3/shop/safe-route/{id}", operationId="shop-safe-route-delete", summary="Delete shop safe-route",
 *      security={{"sanctum": {} }}, tags={"shop safe-route"},
 *   @OA\Parameter(name="id",in="path", description="ID of product in database", required=true,
 *   @OA\Schema(type="integer", format="int64",)),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class SafeRouteRequest extends ApiRequest
{
    public function rules(): array
    {   $required = 'required|integer|exists:shops,id';
        if ('PUT' === $this->getMethod()) {
            $required = 'nullable';
        }

        return [
            'shop_id'      => $required,
            'safe_shop_id' => 'required|integer',
            'token'        => 'required|string',
        ];
    }
}