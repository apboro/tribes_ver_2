<?php

namespace App\Http\ApiRequests\Shop;

use App\Http\ApiRequests\ApiRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * @OA\Get(
 * path="/shop/legal/privacy/{id}",
 * operationId="get-shop-privacy",
 * summary= "Get shop privacy",
 * security= {{"sanctum": {} }},
 * tags= {"Shop Legal"},
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
class ApiLegalShop extends ApiRequest
{
    public function all($keys = null)
    {
        return ['shopId' => $this->route('shopId')] + parent::all();
    }

    public function rules(): array
    {
        return [
            'shopId' => ['required', 'integer', Rule::exists('shops', 'id')],
        ];
    }
}