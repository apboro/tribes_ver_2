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
 *          restrict_accumulate_rate_period - enum from [first_day_of_year, first_day_of_month, first_day_of_week]",
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             encoding={
 *                  "community_ids[]": {
 *                      "explode": true,
 *                  },
 *                  "keyword_rate_up[]":{
 *                      "explode": true,
 *                  },
 *                "keyword_rate_down[]":{
 *                      "explode": true,
 *                  }
 *              },
 *             @OA\Schema(
 *                 @OA\Property(property="title",type="string", example="Who can rate?"),
 *                 @OA\Property(property="who_can_rate",type="string",example="all"),
 *                 @OA\Property(property="restrict_rate_member_period",type="integer", example="1"),
 *                 @OA\Property(property="delay_start_rules_seconds",type="integer", example=10),
 *                 @OA\Property(property="delay_start_rules_messages",type="integer", example=10),
 *
 *                 @OA\Property(property="show_rating_tables",type="boolean", example=true),
 *                 @OA\Property(property="show_rating_tables_period",type="string", example="first_day_of_year"),
 *                 @OA\Property(property="show_rating_tables_time",type="string", example="10:20"),
 *                 @OA\Property(property="show_rating_tables_number_of_users",type="integer", example=10),
 *                 @OA\Property(property="show_rating_tables_image",type="file"),
 *                 @OA\Property(property="show_rating_tables_message",type="string", example="You can rate up to 10 users per month"),
 *
 *                 @OA\Property(property="notify_about_rate_change",type="boolean", example=true),
 *                 @OA\Property(property="notify_about_rate_change_points",type="integer", example=10),
 *                 @OA\Property(property="notify_about_rate_change_image",type="file"),
 *                 @OA\Property(property="notify_about_rate_change_message",type="string", example="You can rate"),
 *
 *                 @OA\Property(property="restrict_accumulate_rate",type="boolean", example=true),
 *                 @OA\Property(property="restrict_accumulate_rate_period",type="string", example="first_day_of_year"),
 *                 @OA\Property(property="restrict_accumulate_rate_image",type="file"),
 *                 @OA\Property(property="restrict_accumulate_rate_message",type="string", example="You can accum"),
 *                 @OA\Property(property="community_ids[]",type="array",@OA\Items(type="integer")),
 *                 @OA\Property(property="keyword_rate_up[]",type="array",@OA\Items(type="string")),
 *                 @OA\Property(property="keyword_rate_down[]",type="array",@OA\Items(type="string")),
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
            'keyword_rate_up' => 'array',
            'keyword_rate_down' => 'array',

            'title' => 'required|string|max:120',
            'who_can_rate' => 'string',
            'restrict_rate_member_period' => 'integer',
            'delay_start_rules_seconds' => 'integer',
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
            'notify_about_rate_change_time' => 'string',
            'notify_about_rate_change_message' => 'string',
            'restrict_accumulate_rate' => 'bool',

            'restrict_accumulate_rate_period' => 'string',
            'restrict_accumulate_rate_image' => 'image|nullable',
            'restrict_accumulate_rate_message' => 'string',

            'community_ids' => 'array',
            'community_ids.*' => 'integer',
        ];
    }


    public function prepareForValidation(): void
    {

        $this->merge([
            'show_rating_tables' => $this->toBoolean($this->show_rating_tables)
        ]);
        $this->merge([
            'notify_about_rate_change' => $this->toBoolean($this->notify_about_rate_change)
        ]);
        $this->merge([
            'restrict_accumulate_rate' => $this->toBoolean($this->restrict_accumulate_rate)
        ]);
    }


    /**
     * Convert to boolean
     *
     * @param $booleable
     * @return boolean
     */
    private function toBoolean($booleable)
    {
        return filter_var($booleable, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }


}
