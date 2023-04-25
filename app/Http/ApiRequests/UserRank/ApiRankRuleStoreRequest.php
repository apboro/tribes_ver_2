<?php


namespace App\Http\ApiRequests\UserRank;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Post(
 *  path="/api/v3/chats/rank",
 *  operationId="chats-rank-rule-add",
 *  summary="Add rank rule with ranks",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Rank Rules"},
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *                 @OA\Property(property="rule_name", type="string"),
 *                 @OA\Property(property="ranks",type="array", @OA\Items(
 *                        @OA\Property(property="name",type="string"),
 *                        @OA\Property(property="reputation_value_to_achieve",type="integer")
 *                 )),
 *                 @OA\Property(property="period_until_reset",type="string"),
 *                 @OA\Property(property="rank_change_in_chat",type="boolean"),
 *                 @OA\Property(property="rank_change_message",type="string"),
 *                 @OA\Property(property="first_rank_in_chat",type="boolean"),
 *                 @OA\Property(property="first_rank_message",type="string"),
 *                 ),
 *         ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiRankRuleStoreRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'rule_name'                   => ['required', 'string', 'max:120'],
            'ranks'                       => ['required', 'array'],
            'period_until_reset'          => ['required', 'string'],
            'rank_change_in_chat'         => ['required','boolean'],
            'rank_change_message'         => ['nullable'],
            'first_rank_in_chat'          => ['required','boolean'],
            'first_rank_message'          => ['nullable'],
        ];
    }

}