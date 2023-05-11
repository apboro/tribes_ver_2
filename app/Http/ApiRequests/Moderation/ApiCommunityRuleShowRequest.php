<?php

namespace App\Http\ApiRequests\Moderation;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(path="/api/v3/chats/rules/{uuid}",
 *     tags={"Chats Moderation"},
 *     summary="Show chat rules by UUID",
 *     operationId="show-chat-rule-by-id",
 *     security={{"sanctum": {} }},
 *     @OA\Parameter(
 *         name="uuid",
 *         in="path",
 *         description="UUID of chat rule in database",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
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
        $data['uuid'] = $this->route('uuid');

        return $data;
    }

    public function rules(): array
    {
        return [
            'uuid' => 'required|string|exists:moderation_rules,uuid'
        ];
    }

    public function messages(): array
    {
        return [
            'uuid.required' => $this->localizeValidation('chat_rule.id_required'),
            'uuid.integer' => $this->localizeValidation('chat_rule.id_integer'),
            'uuid.exists' => $this->localizeValidation('chat_rule.id_exists'),
        ];
    }
}
