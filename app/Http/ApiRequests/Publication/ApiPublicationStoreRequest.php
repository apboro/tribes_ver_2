<?php

namespace App\Http\ApiRequests\Publication;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *  path="/api/v3/publications",
 *  operationId="publication-add",
 *  summary="Add publication",
 *  security={{"sanctum": {} }},
 *  tags={"Publication"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(property="title",type="string"),
 *                 @OA\Property(property="description",type="string"),
 *                 @OA\Property(property="is_active",type="boolean"),
 *                 @OA\Property(property="price",type="integer"),
 *                 @OA\Property(property="background_image",type="file"),
 *                 ),
 *             ),
 *         ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiPublicationStoreRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'background_image' => 'nullable|image|max:10240',
            'price' => 'nullable|integer',
            'parts' => 'nullable|array',
        ];
    }

}
