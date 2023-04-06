<?php

namespace App\Http\ApiRequests;

/**
 * @OA\Post(
 *  path="/api/v3/user/community-users/remove_from_list",
 *  operationId="community-user-list-remove",
 *  summary="Remove user from list (black, mute, ban, white)",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Users"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *            mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="telegram_id", type="integer"),
 *                 @OA\Property(property="community_ids", type="array", @OA\Items(type="integer")),
 *                 @OA\Property(property="banned", type="boolean", example="false"),
 *                 @OA\Property(property="muted", type="boolean", example="false"),
 *                 @OA\Property(property="whitelisted", type="boolean", example="false"),
 *                 @OA\Property(property="blacklisted", type="boolean", example="false"),
 *                 @OA\Property(property="is_spammer", type="integer", example=1),
 *             ),
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK",@OA\JsonContent())
 *)
 */
class ApiCommunityUserListRemoveRequest extends ApiRequest
{

}