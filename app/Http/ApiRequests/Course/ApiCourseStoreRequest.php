<?php

namespace App\Http\ApiRequests\Course;
use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\POST(
 *     path="/api/v3/courses",
 *     tags={"Courses"},
 *     summary="Add course",
 *     operationId="course-add",
 *     security={{"sanctum": {} }},
 *      @OA\Response(response=200, description="OK")
 *)
 */
class ApiCourseStoreRequest extends ApiRequest
{

}
