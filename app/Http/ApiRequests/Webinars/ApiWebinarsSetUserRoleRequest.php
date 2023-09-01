<?php

namespace App\Http\ApiRequests\Webinars;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\GET(
 *  path="/api/v3/webinars/register-user/{uuid}",
 *  operationId="webinars-show-by-uuid",
 *  summary="Show webinar by uudid",
 *  security={{"sanctum": {} }},
 *  tags={"Webinars"},
 *     @OA\Parameter(
 *         name="uuid",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 * @OA\Response(response=200, description="OK")
 *)
 */
class ApiWebinarsSetUserRoleRequest extends ApiRequest
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
            'uuid' => 'required|string|exists:webinars,uuid'
        ];
    }
}
