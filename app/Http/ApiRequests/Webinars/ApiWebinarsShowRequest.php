<?php

namespace App\Http\ApiRequests\Webinars;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\GET(
 *  path="/api/v3/webinars/{id}",
 *  operationId="webinars-show-by-id",
 *  summary="Show webinar by id",
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
class ApiWebinarsShowRequest extends ApiRequest
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
