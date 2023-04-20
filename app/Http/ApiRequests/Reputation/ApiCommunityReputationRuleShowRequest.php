<?php

namespace App\Http\ApiRequests\Reputation;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(path="/api/v3/chats/rate/{id}",
 *     tags={"Chats Reputation"},
 *     summary="Show chat reputation by ID",
 *     operationId="chats-show-chat-repuataion",
 *     security={{"sanctum": {} }},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of chat reputation in database",
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
class ApiCommunityReputationRuleShowRequest extends ApiRequest
{
    public function all($keys = null)
    {
        $data = parent::all();
        $data['id'] = $this->route('id');

        return $data;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:community_reputation_rules,id',
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
