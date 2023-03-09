<?php

namespace App\Http\ApiRequests;

/**
 * @OA\Get(
 *  path="/api/v3/subscription/recurrent",
 *  operationId="subscription_recurrent_change",
 *  summary="Subscription Recurrent Change",
 *  security={{"sanctum": {} }},
 *  tags={"User"},
  *      @OA\Response(response=200, description="Subscription Recurrent Changed", @OA\JsonContent(
 *          @OA\Property(property="message", type="string", nullable=true),
 *          @OA\Property(property="payload", type="array", @OA\Items(), example={}))
 *      ),
 *      @OA\Response(response=422, description="Validation Error", @OA\JsonContent(ref="#/components/schemas/api_response_validation_error")),
 *      @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/api_response_unauthorized")),
 *
 *)
 */
class ApiChangeSubscriptionRecurrentRequest extends ApiRequest
{



}