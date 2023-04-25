<?php


namespace App\Http\ApiRequests\UserRank;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Post(
 *  path="/api/v3/chats/rank",
 *  operationId="chats-rank-rule-add",
 *  summary="Add rank rule",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Rank Rules"},
 *     @OA\RequestBody(
 *         description="
 *          rank_change_in_chat - enum from [вкл,выкл]
 *          first_rank_in_chat - enum from [вкл,выкл]",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="rule_name",type="string"),
 *                 @OA\Property(property="rank_names",type="array", @OA\Items(type="string")),
 *                 @OA\Property(property="reputation_value_to_achieve",type="integer"),
 *
 *                 @OA\Property(property="period_until_reset",type="string"),
 *                 @OA\Property(property="rank_change_in_chat",type="string"),
 *                 @OA\Property(property="rank_change_message",type="string"),
 *                 @OA\Property(property="first_rank_in_chat",type="string"),
 *                 @OA\Property(property="first_rank_message",type="string"),
 *                 ),
 *             ),
 *         ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiRankRuleStoreRequest extends ApiRequest
{
    public function rules(): array
    {
        $enum = ['вкл','выкл'];

        return [
            'rule_name'                   => ['required','string','max:120'],
            'rank_names'                  => ['required','array'],
            'reputation_value_to_achieve' => ['required','integer'],
            'period_until_reset'          => ['required','string'],
            'rank_change_in_chat'         => ['sometimes','nullable','in:вкл,выкл'],
            'rank_change_message'         => ['sometimes','nullable'],
            'first_rank_in_chat'          => ['sometimes','nullable','in:вкл,выкл'],
            'first_rank_message'          => ['sometimes','nullable'],
        ];
    }
}