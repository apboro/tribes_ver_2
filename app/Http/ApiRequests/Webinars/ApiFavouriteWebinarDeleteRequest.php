<?php

namespace App\Http\ApiRequests\Webinars;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Delete(
 *  path="/api/v3/webinars/favourite/{id}",
 *  operationId="webinars-favourite-delete",
 *  summary="Delete webinar from favorite list",
 *  security={{"sanctum": {} }},
 *  tags={"Webinar Favourite"},
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
final class ApiFavouriteWebinarDeleteRequest extends ApiRequest
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
