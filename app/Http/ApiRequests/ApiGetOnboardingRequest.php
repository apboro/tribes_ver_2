<?php

namespace App\Http\ApiRequests;

/**
 * @OA\Get(path="/api/v3/onboarding",
 *  operationId="onboarding entity",
 *  summary="Get list of onboarding rules with greeting message",
 *  security={{"sanctum": {} }},
 *  tags={"Onboarding"},
 *
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiGetOnboardingRequest extends ApiRequest
{

}