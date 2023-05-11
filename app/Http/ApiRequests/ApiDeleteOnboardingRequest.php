<?php

namespace App\Http\ApiRequests;


/**
 * @OA\Delete(
 * path="/api/v3/onboarding/{onboarding_uuid}",
 *  operationId="delete_onboarding_rule",
 *  summary="Delete onboarding rule",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Onboarding"},
 *     @OA\Parameter(
 *         name="onboarding_uuid",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *  )
 */
class ApiDeleteOnboardingRequest extends ApiRequest
{

}