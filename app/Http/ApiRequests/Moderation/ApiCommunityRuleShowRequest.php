<?php

namespace App\Http\ApiRequests\Moderation;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(path="/api/v3/chats/rules/{id}",
 *     tags={"Chats Moderation"},
 *     summary="Show chat rules by ID",
 *     operationId="show-chat-rule-by-id",
 *     security={{"sanctum": {} }},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of chat rule in database",
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
class ApiCommunityRuleShowRequest extends ApiRequest
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
            'id' => 'required|integer|exists:community_rules,id'
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => $this->localizeValidation('chat_rule.id_required'),
            'id.integer' => $this->localizeValidation('chat_rule.id_integer'),
            'id.exists' => $this->localizeValidation('chat_rule.id_exists'),
        ];
    }
}
