<?php

namespace App\Http\ApiRequests;


/**
 * @OA\Post(
 *  path="/api/v3/subscription/pay",
 *  operationId="subscription_pay",
 *  summary="Pay for Subscription",
 *  security={{"sanctum": {} }},
 *  tags={"User"},
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
 *
 *)
 */
class ApiSubscriptionPayRequest extends ApiRequest
{

    public function rules():array
    {
        return [
            'subscription_id' => 'required|integer',
        ];
    }

    public function messages():array
    {
        return [
            'subscription.required'=> $this->localizeValidation('subscription.required')
        ];
    }


}