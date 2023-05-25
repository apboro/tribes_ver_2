<?php

namespace App\Http\ApiRequests;

/**
 * @OA\Post(
 *  path="/api/v3/userbot_session",
 *  operationId="userbot_session",
 *  summary="Userbot session",
 *  security={{"sanctum": {} }},
 *  tags={"UserBot"},
 *     @OA\RequestBody(
 *      @OA\JsonContent(
 *           @OA\Property(property="session_string", type="string", example="79500168570gdfgdfgd"),
 *         ),
 *      ),
 *      @OA\Response(response=200, description="Phone confirmed successfully")
 * )
 */
class ApiStoreUserBotSessionRequest extends ApiRequest
{
    public function rules():array
    {
        return [
            'session_string' => 'required|string',
        ];
    }
}