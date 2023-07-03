<?php

namespace App\Http\ApiRequests\Publication;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *  path="/api/v3/publications/{id}",
 *  operationId="publication-edit",
 *  summary="Edit publication",
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
class ApiPublicationUpdateRequest extends ApiRequest
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
            'id' => 'required|integer|exists:publications,id',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'background_image' => 'nullable|image|max:10240',
            'price' => 'nullable|integer',
        ];
    }
}
