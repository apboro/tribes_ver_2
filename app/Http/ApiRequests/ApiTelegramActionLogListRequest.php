<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *  path="/api/v3/user/bot/action-log",
 *  operationId="bot-action-list",
 *  summary="Get paginated list of bot action in community owned by auth user",
 *  security={{"sanctum": {} }},
 *  tags={"Bot actions list"},
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiTelegramActionLogListRequest extends ApiRequest
{

}
