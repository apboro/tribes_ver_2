<?php

namespace App\Http\ApiRequests\Publication;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\DELETE(
 *  path="/api/v3/publications/{id}",
 *  operationId="publication-delete",
 *  summary="Delete publication",
 *  security={{"sanctum": {} }},
 *  tags={"Publication"},
 *     @OA\Parameter(name="id",in="path",
 *         description="ID of publication in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiPublicationDeleteRequest extends ApiRequest
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
            'id' => 'required|exists:publications,id'
        ];
    }

}
