<?php

namespace App\Http\ApiRequests\Publication;

use App\Http\ApiRequests\ApiRequest;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *  path="/api/v3/visited/publications",
 *  operationId="visited-publication-add",
 *  summary="Add publication to visited list",
 *  security={{"sanctum": {} }},
 *  tags={"Publication Visited"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json", *
 *             @OA\Schema(
 *                 @OA\Property(property="publication_id",type="integer",example="1"),
 *                 ),
 *             ),
 *
 *     ),
 * @OA\Response(response=200, description="OK")
 *)
 */
class ApiVisitedPublicationStoreRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'publication_id' => 'required|exists:publications,id'
        ];
    }
}
