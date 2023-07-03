<?php

namespace App\Http\ApiRequests\Publication;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\GET(
 *  path="/api/v3/publication/{uuid}",
 *  operationId="publication-show-by-uuid",
 *  summary="Show publication by uuid",
 *  security={{"sanctum": {} }},
 *  tags={"Publication"},
 *     @OA\Parameter(name="uuid",in="path",
 *         description="Uuid of publication part in database",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiPublicationShowForAllRequest extends ApiRequest
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
            'uuid' => 'required|uuid|exists:publications,uuid'
        ];
    }
}
