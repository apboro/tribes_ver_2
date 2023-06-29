<?php

namespace App\Http\ApiRequests\Author;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Put(
 * path="/api/v3/authors",
 * operationId="update-author",
 * summary= "Update author",
 * security= {{"sanctum": {} }},
 * tags= {"Author"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *          @OA\Schema(
 *              @OA\Property(property="name", type="string"),
 *              @OA\Property(property="about", type="string"),
 *              @OA\Property(property="photo", type="file", format="binary"),
 *         )
 *      )
 *  ),
 *     @OA\Response(response=200, description="OK"),
 * )
 */
class ApiAuthorUpdateRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:100',
            'about' => 'nullable|string|max:300',
            'photo' => 'nullable|image',
        ];
    }
}
