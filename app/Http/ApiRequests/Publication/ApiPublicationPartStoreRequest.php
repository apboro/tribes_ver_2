<?php

namespace App\Http\ApiRequests\Publication;

use App\Http\ApiRequests\ApiRequest;
use Illuminate\Validation\Rule;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *  path="/api/v3/publication-parts",
 *  operationId="publication-part-add",
 *  summary="Add publication part",
 *  security={{"sanctum": {} }},
 *  tags={"Publication part"},
 *     @OA\RequestBody(
 *         description="
 *          type - enum from [1 (text),2 (video mp4),3 (audio),4 (image png, jpg, jpeg, gif),5(other file pptx, pdf, excel, doc), 6 (header)]",
 * @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(property="publication_id",type="integer",example="1"),
 *                 @OA\Property(property="type",type="integer",example="1"),
 *                 @OA\Property(property="order",type="integer",example="1"),
 *                 @OA\Property(property="text",type="string"),
 *                 @OA\Property(property="file",type="file"),
 *                 ),
 *             ),
 *         ),
 * @OA\Response(response=200, description="OK")
 *)
 */
class ApiPublicationPartStoreRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'type' => 'required|integer|between:1,6',
            'publication_id' => 'required|integer|exists:publications,id',
            'text' => Rule::when($this->type == 1 || $this->type == 6, 'required|max:50000'),
            'file' => [
                Rule::when($this->type == 2, 'required|mimes:mp4|max:2100000'),
                Rule::when($this->type == 3, 'required'),
                Rule::when($this->type == 4, 'required|image|max:10000'),
                Rule::when($this->type == 5, 'required|mimes:|max:100000'),
            ],
        ];;
    }
}
