<?php

namespace App\Http\ApiRequests\Subscription;

///**
// * @OA\Post(path="/api/v3/subscription",
// *     tags={"User"},
// *     summary="Show subscription",
// *     operationId="SubscriptionInfo",
// *     security={{"sanctum": {} }},
// *
// *     @OA\RequestBody(
// *        @OA\JsonContent(
// *           @OA\Property(property="slug", type="string")
// *        )
// *     ),
// *     @OA\Response(response=200, description="Subscription List OK", @OA\JsonContent(
// *            @OA\Property(property="data", type="array",
// *                @OA\Items(
// *                    @OA\Schema (ref="#/components/schemas/subscriptionResource"),
// *                ),
// *            ),
// *            @OA\Property(property="message", type="string", nullable=true),
// *            @OA\Property(property="payload", type="array", @OA\Items(), example={}))
// *     ),
// *     @OA\Response(response=401, description="User not authorized", @OA\JsonContent(ref="#/components/schemas/api_response_unauthorized")),
// *
// *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
// *
// *     @OA\Response(response=500, description="Server Error", @OA\JsonContent(ref="#/components/schemas/api_response_server_error")),
// * )
// */
use App\Http\ApiRequests\ApiRequest;

class ApiShowSubscriptionRequest extends ApiRequest
{

}