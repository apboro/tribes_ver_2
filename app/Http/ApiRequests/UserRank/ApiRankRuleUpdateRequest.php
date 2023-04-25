<?php


namespace App\Http\ApiRequests\UserRank;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Put(
 *  path="/api/v3/chats/rank/{id}",
 *  operationId="chats-rank-rule-edit",
 *  summary="Edit rank rule",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Rank Rules"},
 *     @OA\Parameter(name="id",in="path",description="ID of rank rule in database",required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\RequestBody(
 *         description="
 *          rank_change_in_chat - enum from [вкл,выкл]
 *          first_rank_in_chat - enum from [вкл,выкл]
 *          rank_ids - must be real id in table ranks",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="rule_name",type="string"),
 *                 @OA\Property(property="ranks",type="array", @OA\Items(
 *                        @OA\Property(property="id",type="integer")
 *                        @OA\Property(property="name",type="string"),
 *                        @OA\Property(property="reputation_value_to_achieve",type="integer")
 *                 )),
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
class ApiRankRuleUpdateRequest extends ApiRequest
{
    public function all($keys = null): array
    {
        $data = parent::all();
        $data['id'] = $this->route('id');

        return $data;
    }

    public function rules(): array
    {
        return [
            'rule_name'                   => ['required','string','max:120'],
            'ranks'                       => ['required','array'],
            'period_until_reset'          => ['required','string'],
            'rank_change_in_chat'         => ['required','boolean'],
            'rank_change_message'         => ['nullable'],
            'first_rank_in_chat'          => ['required','boolean'],
            'first_rank_message'          => ['nullable'],
        ];
    }
}