<?php

namespace App\Http\Controllers\API;

/**
 * @OA\Info(
 *     title="Manager Api documentation",
 *     version="1.0.0",
 *     @OA\Contact(
 *         email=L5_SWAGGER_CONST_EMAIL
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 * )
 *
 * @OA\Tag(
 *     name="PaymentController",
 *     description="Платежи в админ панели"
 * )
 *
 * @OA\Tag(
 *     name="UserController",
 *     description="Пользователи в админ панели"
 * )
 *
 * @OA\Tag(
 *     name="LoginController",
 *     description="Аутентификация в админ панели"
 * )
 *
 * @OA\Tag(
 *     name="CommunityController",
 *     description="Сообщества в админ панели"
 * )
 *
 */

class SwaggerController extends \App\Http\Controllers\Controller {}