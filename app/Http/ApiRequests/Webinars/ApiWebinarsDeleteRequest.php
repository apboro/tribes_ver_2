<?php

namespace App\Http\ApiRequests\Webinars;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Delete(
 *  path="/api/v3/webinars/{id}",
 *  operationId="webinars-delete",
 *  summary="Delete webinars",
 *  security={{"sanctum": {} }},
 *  tags={"Webinars"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 * @OA\Response(response=200, description="OK")
 *)
 */
class ApiWebinarsDeleteRequest extends ApiRequest
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
            'id' => 'required|integer|exists:webinars,id'
        ];
    }
}
