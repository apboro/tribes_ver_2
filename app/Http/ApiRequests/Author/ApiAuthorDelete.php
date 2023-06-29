<?php

namespace App\Http\ApiRequests\Author;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Delete(
 * path="/api/v3/authors",
 * operationId="delete-author",
 * summary= "Delete author",
 * security= {{"sanctum": {} }},
 * tags= {"Author"},
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiAuthorDelete extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
