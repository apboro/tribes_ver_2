<?php

namespace App\Http\ApiRequests\Community;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Delete(
 *  path="/api/v3/chats/tags/{tagId}",
 *  operationId="DeleteTag",
 *  summary="Delete Tag by tagId",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Tags"},
 *     @OA\Parameter(
 *         name="tagId",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *
 *      @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *)
 */

class ApiTagDeleteRequest extends ApiRequest
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
            'id' => 'required|integer|exists:tags'
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => $this->localizeValidation('tag.id_required'),
            'id.integer' => $this->localizeValidation('tag.id_integer'),
            'id.exists' => $this->localizeValidation('tag.id_exists'),
        ];
    }
}
