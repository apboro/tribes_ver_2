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
 *          notify_type - enum from [common,all]",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="name",type="string"),
 *                 @OA\Property(property="who_can_rate",type="string",  example="all"),
 *                 @OA\Property(property="rate_period",type="integer"),
 *                 @OA\Property(property="rate_member_period",type="integer"),
 *                 @OA\Property(property="rate_reset_period",type="integer"),
 *
 *                 @OA\Property(property="notify_about_rate_change",type="boolean", example="false"),
 *                 @OA\Property(property="notify_type",type="string",example="common"),
 *                 @OA\Property(property="notify_period",type="integer"),
 *                 @OA\Property(property="notify_content_chat",type="string"),
 *                 @OA\Property(property="notify_content_user",type="string"),
 *
 *                 @OA\Property(property="public_rate_in_chat",type="boolean", example="false"),
 *                 @OA\Property(property="type_public_rate_in_chat",type="integer"),
 *                 @OA\Property(property="rows_public_rate_in_chat",type="integer"),
 *                 @OA\Property(property="text_public_rate_in_chat",type="string"),
 *                 @OA\Property(property="period_public_rate_in_chat",type="integer",example=1),
 *
 *                 @OA\Property(property="count_for_new",type="boolean",example=false),
 *                 @OA\Property(property="start_count_for_new",type="integer"),
 *                 @OA\Property(property="count_reaction",type="integer"),
 *
 *                 @OA\Property(property="keyword_rate_up",type="array",@OA\Items(type="string")),
 *                 @OA\Property(property="keyword_rate_down",type="array",@OA\Items(type="string")),
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
            'who_can_rate' => 'required|in:all,owner,owner_and_admin',

            'keyword_rate_up' => 'required|array',
            'keyword_rate_down' => 'required|array',

            'rate_period' => 'integer',
            'rate_member_period' => 'integer',
            'rate_reset_period' => 'integer',

            'notify_about_rate_change' => 'boolean',
            'notify_type' => 'in:common,all',
            'notify_period' => 'integer',
            'notify_content_chat' => 'string',
            'notify_content_user' => 'string',

            'public_rate_in_chat' => 'boolean',
            'type_public_rate_in_chat' => 'integer',
            'rows_public_rate_in_chat' => 'integer',
            'text_public_rate_in_chat' => 'string',
            'period_public_rate_in_chat' => 'integer',

            'count_for_new' => 'boolean',
            'start_count_for_new' => 'integer',

            'count_reaction' => 'integer',

            'community_ids'=>'array',
            'community_ids.*'=>'integer',
        ];
    }

    public function passedValidation(): void
    {
        if(!$this->boolean('public_rate_in_chat')){
            $this->request->set('type_public_rate_in_chat',null);
            $this->request->set('rows_public_rate_in_chat',null);
            $this->request->set('text_public_rate_in_chat',null);
            $this->request->set('period_public_rate_in_chat',null);
        }

        if(!$this->boolean('notify_about_rate_change')){
            $this->request->set('notify_type',null);
            $this->request->set('notify_period',null);
            $this->request->set('notify_content_chat',null);
            $this->request->set('notify_content_user',null);
        }

        if($this->boolean('count_for_new')){
            $this->request->set('start_count_for_new',null);
        }
    }

}
