<?php

namespace App\Http\ApiRequests;


/**
 * @OA\Get(path="/api/v3/onboarding/{onboarding_uuid}",
 *     tags={"Chats Onboarding"},
 *     summary="Show chat onboarding with UUID",
 *     operationId="onboarding_by_uuid",
 *     security={{"sanctum": {} }},
 *       @OA\Parameter(
 *         name="onboarding_uuid",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiShowOnboardingRequest extends ApiRequest
{

}