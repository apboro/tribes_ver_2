<?php

namespace App\Http\ApiRequests\Admin;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Put(
 *  path="/api/v3/manager/users/{id}",
 *  operationId="admin-users-change-comission",
 *  summary="Update user comission by user id",
 *  security={{"sanctum": {} }},
 *  tags={"Admin users"},
 *     @OA\Parameter(name="id",in="path",description="ID of user in database",required=true,@OA\Schema(type="integer",format="int64")),
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="commission", type="numeric", example="10"),
 *             ),
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK",@OA\JsonContent())
 *)
 */
class ApiUserManagerComissionRequest extends ApiRequest
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
            'id'=>'required|integer|min:1',
            'commission'=>'required|numeric'
        ];
    }

    public function messages():array
    {
        return [
            'id.required' => $this->localizeValidation('manager.user_id_required'),
            'id.integer' => $this->localizeValidation('manager.user_id_integer'),
            'commission.required'=>$this->localizeValidation('commission.required'),
            'commission.numeric'=>$this->localizeValidation('commission.numeric'),
        ];
    }
}
