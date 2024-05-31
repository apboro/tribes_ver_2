<?php

namespace App\Http\ApiRequests\User;

use App\Http\ApiRequests\ApiRequest;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(path="/api/v3/yookassa/get_oauth_link", operationId="yookassa-get_oauth_link", summary="get link for receive oauth",
 *  security={{"sanctum": {} }}, tags={"user unitpay-key"},
 *  @OA\Parameter(name="shop_id", in="query", description="shop_id",required=true,@OA\Schema(type="integer",)),
 *  @OA\Response(response=200, description="OK"),
 *  @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */

class YookassaKeyRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
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