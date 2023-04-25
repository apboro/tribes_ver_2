<?php

namespace App\Http\ApiRequests;


/**
 * @OA\Get(path="/api/v3/chats/users/reputation",
 *     tags={"Chats Reputation"},
 *     summary="Show reputation list of all users in all chats of Auth user",
 *     operationId="all-user-reputations-list",
 *     security={{"sanctum": {} }},
 * @OA\Parameter(
 *         name="offset",
 *         in="query",
 *         description="Begin records from number {offset}",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="limit",
 *         in="query",
 *         description="Total records to display",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *         )
 *     ),
 *
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiGetTelegramUsersReputationRequest extends ApiRequest
{

}

