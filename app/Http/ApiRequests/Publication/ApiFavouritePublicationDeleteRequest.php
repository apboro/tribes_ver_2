<?php

namespace App\Http\ApiRequests\Publication;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Delete(
 *  path="/api/v3/favourite/publications/{id}",
 *  operationId="publications-favourite-delete",
 *  summary="Delete publication from favorite list",
 *  security={{"sanctum": {} }},
 *  tags={"Publication Favorite"},
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
class ApiFavouritePublicationDeleteRequest extends ApiRequest
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
            'id' => 'required|integer|exists:publications,id'
        ];
    }
}
