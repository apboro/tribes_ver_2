<?php

namespace App\Http\ApiRequests;

use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *  path="/api/v3/chats/rate",
 *  operationId="chats-rate-rule-add",
 *  summary="Add rate rules",
 *  security={{"sanctum": {} }},
 *  tags={"Chats rate Rules"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="name",type="string"),
 *                 @OA\Property(property="who_can_rate",type="integer"),
 *                 @OA\Property(property="rate_period",type="integer"),
 *                 @OA\Property(property="rate_member_period",type="integer"),
 *                 @OA\Property(property="rate_reset_period",type="integer"),
 *
 *                 @OA\Property(property="notify_about_rate_change",type="integer"),
 *                 @OA\Property(property="notify_type",type="integer"),
 *                 @OA\Property(property="notify_period",type="integer"),
 *                 @OA\Property(property="notify_content_chat",type="string"),
 *                 @OA\Property(property="notify_content_user",type="string"),
 *
 *                 @OA\Property(property="public_rate_in_chat",type="integer"),
 *                 @OA\Property(property="type_public_rate_in_chat",type="integer"),
 *                 @OA\Property(property="rows_public_rate_in_chat",type="integer"),
 *                 @OA\Property(property="text_public_rate_in_chat",type="string"),
 *                 @OA\Property(property="period_public_rate_in_chat",type="string"),
 *
 *                 @OA\Property(property="count_for_new",type="integer"),
 *                 @OA\Property(property="start_count_for_new",type="integer"),
 *                 @OA\Property(property="count_reaction",type="integer"),
 *
 *                 @OA\Property(property="community_ids",type="array",@OA\Items(type="integer",),
 *                 ),
 *             ),
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiCommunityReputationRuleStoreRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:120',
            'who_can_rate' => 'required|integer',

            'keyword_rate_up' => 'required|array',
            'keyword_rate_down' => 'required|array',

            'rate_period' => 'required|integer',
            'rate_member_period' => 'required|integer',
            'rate_reset_period' => 'required|integer',

            'notify_about_rate_change' => 'required|integer',
            'notify_type' => 'required|integer',
            'notify_period' => 'required|integer',
            'notify_content_chat' => 'required|string',
            'notify_content_user' => 'required|string',

            'public_rate_in_chat' => 'required|integer',
            'type_public_rate_in_chat' => 'required|integer',
            'rows_public_rate_in_chat' => 'required|integer',
            'text_public_rate_in_chat' => 'required|string',
            'period_public_rate_in_chat' => 'required|string',

            'count_for_new' => 'required|integer',
            'start_count_for_new' => 'required|integer',
            'count_reaction' => 'required|integer',

        ];
    }

}
