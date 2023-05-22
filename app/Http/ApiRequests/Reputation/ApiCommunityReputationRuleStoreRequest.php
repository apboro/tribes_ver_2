<?php

namespace App\Http\ApiRequests\Reputation;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Post(
 *  path="/api/v3/chats/rate",
 *  operationId="chats-reputation-rule-add",
 *  summary="Add reputation rules",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Reputation"},
 *     @OA\RequestBody(
 *         description="
 *          who_can_rate - enum from [all, owner, owner_and_admin]
 *          show_rating_tables_period - enum from [first_day_of_year, first_day_of_month, first_day_of_week]
 *          restrict_accumulate_rate_period - enum from [first_day_of_year, first_day_of_month, first_day_of_week]
 *          ",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="title",type="string"),
 *                 @OA\Property(property="who_can_rate",type="string",example="all"),
 *                 @OA\Property(property="restrict_rate_member_period",type="integer"),
 *                 @OA\Property(property="delay_start_rules_time",type="string"),
 *                 @OA\Property(property="delay_start_rules_messages",type="integer"),
 *
 *                 @OA\Property(property="show_rating_tables",type="boolean"),
 *                 @OA\Property(property="show_rating_tables_period",type="string"),
 *                 @OA\Property(property="show_rating_tables_time",type="string"),
 *                 @OA\Property(property="show_rating_tables_number_of_users",type="integer"),
 *                 @OA\Property(property="show_rating_tables_image",type="string"),
 *                 @OA\Property(property="show_rating_tables_message",type="string"),
 *
 *                 @OA\Property(property="notify_about_rate_change",type="boolean"),
 *                 @OA\Property(property="notify_about_rate_change_points",type="integer"),
 *                 @OA\Property(property="notify_about_rate_change_image",type="string"),
 *                 @OA\Property(property="notify_about_rate_change_message",type="string"),
 *
 *                 @OA\Property(property="restrict_accumulate_rate",type="string"),
 *                 @OA\Property(property="restrict_accumulate_rate_period",type="string"),
 *                 @OA\Property(property="restrict_accumulate_rate_image",type="string"),
 *                 @OA\Property(property="restrict_accumulate_rate_message",type="string"),
 *                 @OA\Property(property="community_ids",type="array",@OA\Items(type="integer")),
 *                 ),
 *             ),
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiCommunityReputationRuleStoreRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'keyword_rate_up' => 'required|array',
            'keyword_rate_down' => 'required|array',

            'title' => 'required|string|max:120',
            'who_can_rate' => 'string',
            'restrict_rate_member_period' => 'string',
            'delay_start_rules_time' => 'string',
            'delay_start_rules_total_messages' => 'integer',
            'show_rating_tables' => 'bool',
            'show_rating_tables_period' => 'string',
            'show_rating_tables_time' => 'string',
            'show_rating_tables_number_of_users' => 'integer',
            'show_rating_tables_image' => 'image|nullable',
            'show_rating_tables_message' => 'string',
            'notify_about_rate_change' => 'bool',
            'notify_about_rate_change_points' => 'integer',
            'notify_about_rate_change_image' => 'image|nullable',
            'notify_about_rate_change_message' => 'string',
            'restrict_accumulate_rate' => 'bool',
            'restrict_accumulate_rate_period' => 'string',
            'restrict_accumulate_rate_image' => 'image|nullable',
            'restrict_accumulate_rate_message' => 'string',
            'community_ids' => 'array',
            'community_ids.*' => 'integer',
        ];
    }

}
