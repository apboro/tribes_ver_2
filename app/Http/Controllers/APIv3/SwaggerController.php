<?php

namespace App\Http\Controllers\APIv3;

use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Spodial API documentation",
 *     version="3.0.0",
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 * )
 *
 * @OA\Tag(
 *     name="User",
 *     description="Пользователь"
 * )
 */
class SwaggerController extends Controller
{
}