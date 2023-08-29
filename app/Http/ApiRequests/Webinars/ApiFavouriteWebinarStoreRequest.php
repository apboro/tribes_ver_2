<?php

namespace App\Http\ApiRequests\Webinars;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *  path="/api/v3/webinars/favourite",
 *  operationId="webinars-favorite-add",
 *  summary="Add webinar to favorite list",
 *  security={{"sanctum": {} }},
 *  tags={"Webinar Favourite"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                      @OA\Property(property="webinar_id",type="integer",example="1"),
 *             ),
 *         )
 *     ),
 * @OA\Response(response=200, description="OK")
 *)
 */
final class ApiFavouriteWebinarStoreRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'webinar_id' => 'required|integer|exists:webinars,id'
        ];
    }
}
