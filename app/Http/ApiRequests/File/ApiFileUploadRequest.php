<?php

namespace App\Http\ApiRequests\File;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Post(
 * path="/api/v3/file/upload",
 * operationId="FileUpload",
 * summary= "File upload",
 * security= {{"sanctum": {} }},
 * tags= {"Files"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *          @OA\Schema(
 *              @OA\Property(property="course_id", type="integer"),
 *              @OA\Property(property="file", type="file", format="binary"),
 *         )
 *      )
 *  ),
 *     @OA\Response(response=200, description="OK"),
 * )
 */
class ApiFileUploadRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'course_id' => 'required',
            'file' => 'required|mimes:png,jpg,jpeg',
        ];
    }
}
