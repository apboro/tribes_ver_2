<?php

namespace App\Http\ApiRequests;


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
 *
 *)
 */
class ApiCardListRequest extends ApiRequest
{

}