<?php

namespace App\Http\ApiRequests\Webinars;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\GET(
 *  path="/api/v3/webinar/{uuid}",
 *  operationId="webinar-show-by-uuid",
 *  summary="Show webinar by uuid",
 *  security={{"sanctum": {} }},
 *  tags={"Webinars"},
 *     @OA\Parameter(name="uuid",in="path",
 *         description="Uuid of webinar in database",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiWebinarShowByUuidRequest extends ApiRequest
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
            'uuid' => 'required|uuid|exists:webinars,uuid'
        ];
    }
}
