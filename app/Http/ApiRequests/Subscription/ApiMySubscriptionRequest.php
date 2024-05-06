<?php

namespace App\Http\ApiRequests\Subscription;

/**
 * @OA\Get(path="/api/v3/user/subscription/my",
 *     tags={"User subscription"},
 *     summary="Show user subscription information",
 *     operationId="UserSubscription",
 *     security={{"sanctum": {} }},
 *
 *     @OA\Response(response=200, description="OK"),
 * )
 */

use App\Http\ApiRequests\ApiRequest;

class ApiMySubscriptionRequest extends ApiRequest
{

}