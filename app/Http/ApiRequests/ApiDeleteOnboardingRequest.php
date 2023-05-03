<?php

namespace App\Http\ApiRequests;


/**
 * @OA\Delete(
 * path="/api/v3/onboarding",
 *  operationId="delete_onboarding_rule",
 *  summary="Delete onboarding rule",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Onboarding"},
 *     @OA\RequestBody(
 *      @OA\JsonContent(
 *          @OA\Property(property="onboarding_uuid", type="integer"),
 *      )
 *  ),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *  )
 */
class ApiDeleteOnboardingRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'onboarding_uuid' => 'required|exists:onboardings,uuid'
        ];
    }

}