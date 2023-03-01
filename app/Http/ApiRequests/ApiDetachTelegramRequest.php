<?php

namespace App\Http\ApiRequests;

/**
 * @OA\Post(
 *  path="/api/v3/user/telegram/detach",
 *  operationId="detach_telegram_account",
 *  summary="Detach Telegram Account",
 *  security={{"sanctum": {} }},
 *  tags={"User"},
 *     @OA\Response(response=200, description="Telegram account detached successfully", @OA\JsonContent(
 *         @OA\Property(property="message", type="string", nullable=true),
 *         @OA\Property(property="payload", type="array", @OA\Items(), example={}))
 *      ),
 *      @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/api_response_unauthorized")),
 *
 *)
 */
class ApiDetachTelegramRequest
{

}