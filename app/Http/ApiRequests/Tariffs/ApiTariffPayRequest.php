<?php

namespace App\Http\ApiRequests\Tariffs;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;


/**
 * @OA\Post(
 *  path="/api/v3/pay/tariff",
 *  operationId="pay-for-tariff",
 *  summary="Pay for tariff",
 *  security={{"sanctum": {} }},
 *  tags={"Tariffs"},
 *     @OA\RequestBody(
 *        @OA\JsonContent(
 *                 @OA\Property(property="id",type="integer", example="1"),
 *                 @OA\Property(property="e-mail",type="string", example="test@dev.com"),
 *                 ),
 *         ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiTariffPayRequest extends ApiRequest
{
    public function rules(): array
    {
        return  [
            'id' =>'required|exists:tariffs,id',
            'e-mail' => 'required|string|email',
            'try_trial' => 'boolean'
        ];
    }

}