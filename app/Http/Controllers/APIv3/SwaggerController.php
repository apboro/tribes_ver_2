<?php

namespace App\Http\Controllers\APIv3;

use OpenApi\Attributes as OAT;
#[OAT\Info(
    version: "3.0.0",
    title: "Spodial API ver.3 documentation",
),
    OAT\Tag(
        name: "User",
        description: "User endpoints"
    )
]
/**
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 * )
 */
class SwaggerController extends \App\Http\Controllers\Controller
{
}