<?php

namespace App\Http\ApiRequests\Publication;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\DELETE(
 *  path="/api/v3/publication-parts/{id}",
 *  operationId="publication-part-delete",
 *  summary="Delete publication part",
 *  security={{"sanctum": {} }},
 *  tags={"Publication part"},
 *     @OA\Parameter(name="id",in="path",
 *         description="ID of publication part in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiPublicationPartDeleteRequest extends ApiRequest
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
            'id' => 'required|exists:publication_parts,id'
        ];
    }
}
