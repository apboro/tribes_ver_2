<?php

namespace App\Http\ApiRequests\User;

use App\Http\ApiRequests\ApiRequest;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

/**
 *
 * @OA\POST(
 *  path="/api/v3/user/unitpay-key", operationId="save-users-unitpay-key", summary="save users unitpay-key",
 *  security={{"sanctum": {} }}, tags={"user unitpay-key"},
 *     @OA\Parameter(name="shop_id", in="query", description="shop_id",required=true,@OA\Schema(type="integer",)),
 *     @OA\Parameter(name="project_id", in="query", description="project_id",required=false,@OA\Schema(type="integer",)),
 *     @OA\Parameter(name="secretKey",in="query",description="secretKey",required=false,@OA\Schema(type="string",)),
 * @OA\Response(response=200, description="OK")
 * )
 *
 * @OA\Get(path="/api/v3/user/unitpay-key", operationId="index-unitpay-key", summary="index unitpay-key",
 *  security={{"sanctum": {} }}, tags={"user unitpay-key"},
 *  @OA\Parameter(name="shop_id", in="query", description="shop_id",required=true,@OA\Schema(type="integer",)),
 *  @OA\Response(response=200, description="OK"),
 *  @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 *
 * @OA\DELETE(
 *  path="/api/v3/user/unitpay-key", operationId="unitpay-key-delete", summary="Delete users unitpay-key",
 *     @OA\Parameter(name="shop_id", in="query", description="shop_id",required=true,@OA\Schema(type="integer",)),
 *     security={{"sanctum": {} }}, tags={"user unitpay-key"},
 *   @OA\Response(response=200, description="OK")
 *)
 */

class UnitpayKeyRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'project_id' => 'nullable|int',
            'secretKey'  => 'nullable|string',
            'shop_id' => [
                'required', 'integer', 
                function ($attribute, $value, $fail) {
                    if (!Auth::user()->hasShops($value)) {
                        $fail('Магазин не найден');  
                    }
                },
            ],
        ];
    }
}