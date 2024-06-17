<?php

namespace App\Http\ApiRequests\User;

use App\Http\ApiRequests\ApiRequest;
use App\Rules\UserHasShopRule;
use OpenApi\Annotations as OA;

/**
 * @OA\Put(
 *     path="/api/v3/robokassa-keys",
 *     operationId="robokassa-keys-put",
 *     summary="Set Robokassa keys",
 *     security={{"sanctum": {} }},
 *     tags={"Robokassa"},
 *
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             @OA\Property(property="shop_id", description="Shop identifier", type="integer"),
 *             @OA\Property(property="merchant_login", description="Merchant's login form Robokassa", type="string"),
 *             @OA\Property(property="first_password", description="Merchant's first password form Robokassa", type="string"),
 *             @OA\Property(property="second_password", description="Merchant's second password form Robokassa", type="string"),
 *             required={"shop_id", "merchant_login", "first_password", "second_password"},
 *             example={"shop_id": 1, "merchant_login": "demo", "first_password": "foo", "second_password": "bar"}
 *         )
 *     ),
 *     @OA\Response(response="200", description="OK"),
 *     @OA\Response(response="401", description="Unauthorized"),
 *     @OA\Response(response="422", description="Unprocessable Content"),
 * )
 *
 * @OA\Get(
 *     path="/api/v3/robokassa-keys",
 *     operationId="robokassa-keys-get",
 *     summary="Get Robokassa keys",
 *     security={{"sanctum": {} }},
 *     tags={"Robokassa"},
 *
 *     @OA\Parameter(name="shopId", in="query", description="Shop identifier", required=true, @OA\Schema(type="integer")),
 *
 *     @OA\Response(response="200", description="OK"),
 *     @OA\Response(response="401", description="Unauthorized"),
 *     @OA\Response(response="422", description="Unprocessable Content"),
 * )
 *
 * @OA\Delete(
 *     path="/api/v3/robokassa-keys",
 *     operationId="robokassa-keys-delete",
 *     summary="Delete Robokassa keys",
 *     security={{"sanctum": {} }},
 *     tags={"Robokassa"},
 *
 *     @OA\Parameter(name="shopId", in="query", description="Shop identifier", required=true, @OA\Schema(type="integer")),
 *
 *     @OA\Response(response="200", description="OK"),
 *     @OA\Response(response="401", description="Unauthorized"),
 *     @OA\Response(response="422", description="Unprocessable Content"),
 *     @OA\Response(response="404", description="Not Found"),
 * )
 */
class RobokassaKeyRequest extends ApiRequest
{
    public function rules(): array
    {
        $rules = [];
        if ($this->getMethod() === self::METHOD_PUT) {
            $rules = [
                'merchant_login' => [
                    'required',
                    'string'
                ],
                'first_password' => [
                    'required',
                    'string'
                ],
                'second_password' => [
                    'required',
                    'string'
                ],
            ];
        }

        return [
            'shop_id' => [
                    'required',
                    'integer',
                    new UserHasShopRule,
                ]
        ] + $rules;
    }

    public function prepareForValidation(): void
    {
        if ($this->getMethod() !== self::METHOD_PUT) {
            $this->merge(['shop_id' => $this->route('shopId')]);
        }
    }
}