<?php

namespace App\Http\ApiRequests\Subscription;

///**
// * @OA\Post(
// *  path="/api/v3/subscription/edit",
// *  operationId="subscritpion-edit",
// *  summary="Subscription edit",
// *  security={{"sanctum": {} }},
// *  tags={"Subscription"},
// *      @OA\RequestBody(
// *          @OA\JsonContent(
// *               @OA\Property(property="id", type="integer"),
// *               @OA\Property(property="description", type="string"),
// *               @OA\Property(property="name", type="string"),
// *               @OA\Property(property="price", type="integer"),
// *         )
// *      ),
// *      @OA\Response(response=200, description="Success", @OA\JsonContent(
// *          @OA\Property(property="message", type="string", nullable=true),
// *          @OA\Property(property="payload", type="array", @OA\Items(), example={}))
// *      ),
// *      @OA\Response(response=401, description="Unauthorized"),
// *      @OA\Response(response=400, description="Error: Bad Request"),
// *
// *)
// */
use App\Http\ApiRequests\ApiRequest;

class ApiEditSubscriptionRequest extends ApiRequest
{

}