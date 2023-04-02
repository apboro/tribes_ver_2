<?php

namespace App\Http\ApiRequests\Admin;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(path="/api/v3/manager/users/send-new-password/{id}",
 *     tags={"Admin users"},
 *     summary="Send new password to user",
 *     operationId="admin-users-send-new-password",
 *     security={{"sanctum": {} }},
 *     @OA\Parameter(
 *         name="id",
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
class ApiUserManagerSendPasswordRequest extends ApiRequest
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
            'id'=>'required|integer|min:1'
        ];
    }

    public function messages():array
    {
        return [
            'id.required' => $this->localizeValidation('manager.user_id_required'),
            'id.integer' => $this->localizeValidation('manager.user_id_integer'),
        ];
    }
}
