<?php

namespace App\Http\Requests\Swagger\Manager;

/**
 * @OA\Schema(
 *     title="UserRequest",
 *     description="User filters",
 *      @OA\Property(
 *          property="search",
 *          description="Поиск по имени пользователя, телефону или id",
 *          format="string",
 *          example="test"
 *      ),
 *     @OA\Property(
 *          property="entries",
 *          description="Количество записей на странице",
 *          format="integer",
 *          example=2
 *      ),
 * )
 */
class UserRequest {}
