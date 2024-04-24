<?php

namespace App\Http\Requests\User;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 *
 * @OA\POST(
 *  path="/api/v3/user/legal-info", operationId="store-user-legal-info", summary="store user legal-info",
 *  security={{"sanctum": {} }}, tags={"user legal-info"},
 *     @OA\Parameter(name="name", in="query", description="payer-s name",required=true,@OA\Schema(type="string",)),
 *     @OA\Parameter(name="inn",in="query",description="INN",required=true,@OA\Schema(type="string",)),
 *     @OA\Parameter(name="kpp",in="query",description="KPP",required=false,@OA\Schema(type="string",)),
 *     @OA\Parameter(name="email",in="query",description="email address",required=true,@OA\Schema(type="string",)),
 *     @OA\Parameter(name="phone",in="query",description="Delivery phone",required=false,@OA\Schema(type="string",)),
 *     @OA\Parameter(name="address",in="query",description="address",required=true,@OA\Schema(type="string",)),
 *     @OA\Parameter(name="ogrn",in="query",description="ogrn",required=true,@OA\Schema(type="string",)),
 *     @OA\Parameter(name="additionally",in="query",description="additionally",required=false,@OA\Schema(type="string",)),
 * @OA\Response(response=200, description="OK")
 * )
 *
 * @OA\Get(path="/api/v3/user/legal-info", operationId="index-legal-info", summary="index legal-info",
 *  security={{"sanctum": {} }}, tags={"user legal-info"},
 *  @OA\Response(response=200, description="OK"),
 *  @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 *
 * @OA\Get(path="/api/v3/user/legal-info/{id}", operationId="show legal-info", summary="Show legal-info by id",
 *  security={{"sanctum": {} }}, tags={"user legal-info"},
 *     @OA\Parameter(name="id", in="path", description="ID of legal-info in database", required=true,
 *         @OA\Schema(type="integer", format="int64",)),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 *
 * @OA\Put(path="/api/v3/user/legal-info/{id}",
 *     operationId="upodate legal-info", summary="upodate legal-info", security={{"sanctum": {} }}, tags={"user legal-info"},
 *     @OA\Parameter(name="id", in="path", description="ID of legal-info in database", required=true,
 *         @OA\Schema(type="integer", format="int64",)),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch",
 *     @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *  )
 *
 * @OA\DELETE(
 *  path="/api/v3/user/legal-info/{id}", operationId="legal-info-delete", summary="Delete legal-info",
 *      security={{"sanctum": {} }}, tags={"user legal-info"},
 *   @OA\Parameter(name="id",in="path", description="ID of product in database", required=true,
 *   @OA\Schema(type="integer", format="int64",)),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class LegaLInfoRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $required = 'required|string';
        if('PUT' === $this->getMethod()) {
            $required = 'nullable|string';
        }

        return [
            'name'  => $required,
            'inn'   => $required,
            'kpp'   => 'nullable|string',
            'email' => $required . '|email',
            'phone' => 'nullable|string',
            'address' => $required,
            'ogrn' => $required,
            'additionally' => 'nullable|string',
        ];
    }
}
