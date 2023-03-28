<?php

namespace App\Http\ApiRequests\Profile;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(
 *  path="/api/v3/payment-cards",
 *  operationId="payment-cards-list",
 *  summary="Get cards list",
 *  security={{"sanctum": {} }},
 *  tags={"User Cards"},

 *      @OA\Response(response=200, description="Cards fetched", @OA\JsonContent(
 *          @OA\Property(property="list", type="array", @OA\Items(), example={}, nullable=true),
 *          @OA\Property(property="message", type="string", nullable=true),
 *          @OA\Property(property="payload", type="array", @OA\Items(), example={}))
 *      ),
 *      @OA\Response(response=401, description="Unauthorized"),
 *      @OA\Response(response=400, description="Error: Bad Request"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *
 *)
 */
class ApiCardListRequest extends ApiRequest
{

}