<?php

namespace App\Http\ApiRequests;


/**
 * @OA\Get(path="/api/v3/user/telegram/list",
 *     tags={"User Telegram"},
 *     summary="Show user telegram accounts",
 *     operationId="UserTelegramInfo",
 *     security={{"sanctum": {} }},
 *
 *     @OA\Response(response=200, description="Telegram List fetched", @OA\JsonContent(
 *            @OA\Property(property="data", type="array",
 *                @OA\Items(
 *                    @OA\Schema (ref="#/components/schemas/telegramAccountResource"),
 *                ),
 *                example={{"id": 1, "name": "Jessica Smith", "image": "https://localhost/images/photo.jpg"}}
 *            ),
 *            @OA\Property(property="message", type="string", nullable=true),
 *            @OA\Property(property="payload", type="array", @OA\Items(), example={}))
 *     ),
 *     @OA\Response(response=401, description="User not authorized", @OA\JsonContent(ref="#/components/schemas/api_response_unauthorized")),
 *
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *     @OA\Response(response=400, description="Error", @OA\JsonContent(ref="#/components/schemas/api_response_error")),
 *
 *     @OA\Response(response=500, description="Server Error", @OA\JsonContent(ref="#/components/schemas/api_response_server_error")),
 * )
 */
class ApiListMessengersRequest extends ApiRequest
{

}