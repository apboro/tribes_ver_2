<?php


namespace App\Http\ApiRequests\UserRank;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(path="/api/v3/chats/rank/{id}",
 *     tags={"Chats Rank Rules"},
 *     summary="Show rank rule by ID",
 *     operationId="chats-show-rank-rule",
 *     security={{"sanctum": {} }},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of rank rule in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiRankRuleShowRequest extends ApiRequest
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
            'id' => 'required|integer|exists:rank_rules,id',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => $this->localizeValidation('id_required'),
            'id.integer' => $this->localizeValidation('id_integer'),
            'id.exists' => $this->localizeValidation('id_exists'),
        ];
    }
}