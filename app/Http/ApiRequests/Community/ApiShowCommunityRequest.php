<?php

namespace App\Http\ApiRequests\Community;

use App\Http\ApiRequests\ApiRequest;


/**
 * @OA\Get(path="/api/v3/user/chats/{chatId}",
 *     tags={"Chats"},
 *     summary="Show chat with ID",
 *     operationId="ChatInfoById",
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
 *     ),
 *
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */

class ApiShowCommunityRequest extends ApiRequest
{

    public function all($keys = null)
    {
        $data = parent::all();
        $data['id'] = $this->route('id');

        return $data;
    }

    public function rules():array
    {
        return [
            'id' => 'required|integer|exists:communities',
        ];
    }
}
