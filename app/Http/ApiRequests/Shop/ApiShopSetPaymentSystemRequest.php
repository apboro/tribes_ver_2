<?php

namespace App\Http\ApiRequests\Shop;

use App\Http\ApiRequests\ApiRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 * path="/api/v3/shops/set_payment_system/{id}",
 * operationId="set_payment_system-shops",
 * summary= "Set payment system",
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
 *     @OA\Parameter(name="payment_system",in="query",
 *         description="Name of payment system",
 *         required=true,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiShopSetPaymentSystemRequest extends ApiRequest
{
    public function all($keys = null)
    {
        return [
            'shopId' => $this->route('id'),
            ] + parent::all();
    }

    public function rules(): array
    {
        return [
            'shopId' => [
                'required', 'integer', 
                function ($attribute, $value, $fail) {
                    if (!Auth::user()->hasShops($value)) {
                        $fail('Магазин не найден');  
                    }
                },
            ],
            'payment_system' => ['required', 'string',  Rule::in(array_keys(config('payments.banksList')))],
        ];
    }
}