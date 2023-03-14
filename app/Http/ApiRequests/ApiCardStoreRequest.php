<?php

namespace App\Http\ApiRequests;

/**
 * @OA\Post(
 *  path="/api/v3/payment-cards",
 *  operationId="payment-card-store",
 *  summary="Store bank card",
 *  security={{"sanctum": {} }},
 *  tags={"User Cards"},

 *      @OA\Response(response=200, description="Cards fetched", @OA\JsonContent(
 *          @OA\Property(property="message", type="string", nullable=true),
 *          @OA\Property(property="payload", type="array", @OA\Items(), example={}))
 *      ),
 *      @OA\Response(response=401, description="Unauthorized"),
 *      @OA\Response(response=400, description="Error: Bad Request"),
 *
 *)
 */
class ApiCardStoreRequest extends ApiRequest
{

}