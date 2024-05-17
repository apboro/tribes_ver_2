<?php

namespace App\Http\ApiRequests\User;

use App\Http\ApiRequests\ApiRequest;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

/**
 *
 * @OA\POST(
 *  path="/api/v3/shop/unitpay-metatag", operationId="save-users-unitpay-metatag", summary="save users unitpay-metatag",
 *  security={{"sanctum": {} }}, tags={"user unitpay-metatag"},
 *     @OA\Parameter(name="shop_id", in="query", description="shop_id",required=true,@OA\Schema(type="integer",)),
 *     @OA\Parameter(name="metatag", in="query", description="metatag",required=true,@OA\Schema(type="string",)),
 * @OA\Response(response=200, description="OK")
 * )
 *
 * @OA\Get(path="/api/v3/shop/unitpay-metatag", operationId="index-unitpay-metatag", summary="index unitpay-metatag",
 *  security={{"sanctum": {} }}, tags={"user unitpay-metatag"},
 *  @OA\Parameter(name="shop_id", in="query", description="shop_id",required=true,@OA\Schema(type="integer",)),
 *  @OA\Response(response=200, description="OK"),
 *  @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */

class UnitpayMetatagRequest extends ApiRequest
{
    public function rules(): array
    {
        if ($this->getMethod() === 'POST') {
            return [
                'metatag' => 'required|string',
                'shop_id' => [
                    'required', 'integer',
                    function ($attribute, $value, $fail) {
                        if (!Auth::user()->hasShops($value)) {
                            $fail('Магазин не найден');
                        }
                    }
                ]
            ];
        }

        return ['shop_id' => 'required|integer'];
    }
}