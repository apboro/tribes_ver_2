<?php

namespace App\Http\ApiRequests\Course;

use App\Helper\PseudoCrypt;
use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(path="/api/v3/courses/show/{hash}",
 *     tags={"Courses"},
 *     summary="Show course by hash",
 *     operationId="show-course-by-hash",
 *     security={{"sanctum": {} }},
 *     @OA\Parameter(name="hash",in="path", description="Hash of course id in database",required=true,@OA\Schema(type="string")),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiCourseShowForAllRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'id' => 'required',
        ];
    }

    public function prepareForValidation():void
    {
        $this->merge(['id'=>(int) PseudoCrypt::unhash($this->route('hash'))]);

    }

    public function messages(): array
    {
        return [
            'hash.required'=> $this->localizeValidation('course.hash_required')
        ];
    }

}
