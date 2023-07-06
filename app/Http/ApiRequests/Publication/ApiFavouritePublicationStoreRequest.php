<?php

namespace App\Http\ApiRequests\Publication;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *  path="/api/v3/favourite/publications",
 *  operationId="publications-favorite-add",
 *  summary="Add publication to favorite list",
 *  security={{"sanctum": {} }},
 *  tags={"Publication Favorite"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                      @OA\Property(property="publication_id",type="integer",example="1"),
 *             ),
 *         )
 *     ),
 * @OA\Response(response=200, description="OK")
 *)
 */
class ApiFavouritePublicationStoreRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'publication_id' => 'required|integer|exists:publications,id'
        ];
    }
}
