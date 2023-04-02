<?php

namespace App\Http\ApiRequests\Course;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/v3/courses",
 *     tags={"Courses"},
 *     summary="Show course list",
 *     operationId="course-list",
 *     security={{"sanctum": {} }},
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiCourseListRequest extends ApiRequest
{

}
