<?php

namespace App\Http\ApiRequests\Course;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/v3/courses/{id}",
 *     tags={"Courses"},
 *     summary="Show course",
 *     operationId="course-show",
 *     security={{"sanctum": {} }},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of course in database",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\Response(response=200, description="OK"),
 * )
 */
class ApiCourseShowRequest extends ApiRequest
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
            'id' => 'required|integer|min:1'
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => $this->localizeValidation('course.id_required'),
            'id.integer' => $this->localizeValidation('course.id_integer'),
        ];
    }
}
