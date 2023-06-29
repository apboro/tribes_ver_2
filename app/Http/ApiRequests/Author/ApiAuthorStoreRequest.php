<?php

namespace App\Http\ApiRequests\Author;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;


/**
 * @OA\Post(
 * path="/api/v3/authors",
 * operationId="store-author",
 * summary= "Store author",
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
class ApiAuthorStoreRequest extends ApiRequest
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
