<?php

namespace App\Http\ApiRequests\User;

use App\Http\ApiRequests\ApiRequest;
use App\Rules\UserHasShopRule;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(path="/api/v3/yookassa/exchange", operationId="yookassa-exchange", summary="exchange oauth",
 *  security={{"sanctum": {} }}, tags={"yookassa"},
 *  @OA\Parameter(name="code", in="query", description="code",required=true,@OA\Schema(type="string",)),
 *  @OA\Parameter(name="state", in="query", description="state",required=true,@OA\Schema(type="integer",)),
 *  @OA\Response(response=200, description="OK"),
 *  @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */

class YookassaExchangeRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'code' => 'required',
            'state' => [
                'required', 'integer'],
        ];
    }
}