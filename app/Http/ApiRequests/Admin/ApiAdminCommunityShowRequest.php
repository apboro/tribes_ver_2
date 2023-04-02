<?php

namespace App\Http\ApiRequests\Admin;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(path="/api/v3/manager/communities/{chatId}",
 *     tags={"Admin chats"},
 *     summary="Show chat",
 *     operationId="admin-chat-show",
 *     security={{"sanctum": {} }},
 *     @OA\Parameter(
 *         name="chatId",
 *         in="path",
 *         description="ID of chat in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ), *
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */

class ApiAdminCommunityShowRequest extends ApiRequest
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
            'id'=>'required|integer|min:1|exists:communities'
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => $this->localizeValidation('community.id_required'),
            'id.integer' => $this->localizeValidation('community.id_integer'),
            'id.exists' => $this->localizeValidation('community.not_found'),
        ];
    }
}
