<?php

namespace App\Http\ApiRequests\Subscription;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Post(
 *  path="/api/v3/subscription/pay",
 *  operationId="subscription_pay",
 *  summary="Pay for Subscription",
 *  security={{"sanctum": {} }},
 *  tags={"Subscription"},
 *     @OA\RequestBody(
 *          @OA\JsonContent(
 *                 @OA\Property(property="subscriptin_id",type="integer"),
 *                example={"subscription_id": 2}
 *           )
 *      ),
 *      @OA\Response(response=200, description="Subscription Paid", @OA\JsonContent(
 *          @OA\Property(property="message", type="string", nullable=true),
 *          @OA\Property(property="payload", type="array", @OA\Items(), example={}))
 *      ),
 *      @OA\Response(response=422, description="Validation Error", @OA\JsonContent(ref="#/components/schemas/api_response_validation_error")),
 *      @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/api_response_unauthorized")),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *
 *)
 */
class ApiSubscriptionPayRequest extends ApiRequest
{

    public function rules():array
    {
        return [
            'subscription_id' => 'required|integer|exists:subscriptions,id',
        ];
    }

    public function messages():array
    {
        return [
            'subscription.required'=> $this->localizeValidation('subscription.required')
        ];
    }


}