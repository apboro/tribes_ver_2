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
 *                 @OA\Property(property="rank_change_in_chat",type="boolean"),
 *                 @OA\Property(property="rank_change_message",type="string"),
 *                 @OA\Property(property="first_rank_in_chat",type="boolean"),
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
        return [
            'rule_name'                   => ['required', 'string', 'max:120'],
            'rank_names'                  => ['required', 'array'],
            'reputation_value_to_achieve' => ['required', 'integer'],
            'period_until_reset'          => ['required', 'string'],
            'rank_change_in_chat'         => ['required','boolean'],
            'rank_change_message'         => ['nullable'],
            'first_rank_in_chat'          => ['required','boolean'],
            'first_rank_message'          => ['nullable'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge(['rank_change_in_chat' => $this->toBoolean($this->rank_change_in_chat)]);
        $this->merge(['first_rank_in_chat' => $this->toBoolean($this->first_rank_message)]);
    }

    /**
     * Convert to boolean *
     * @param $booleable * @return boolean
     */
    private function toBoolean($booleable)
    {
        return filter_var($booleable, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }
}