<?php

namespace App\Http\Resources\Swagger\Manager;

/**
* @OA\Schema(
 *     title="UserResource",
 *     description="User response",
 *     @OA\Xml(name="users"),
 *     @OA\Property(
 *          property="current_page",
 *          type="integer",
 *     ),
 *     @OA\Property(
 *          property="data",
 *          type="array",
 *          collectionFormat="multi",
 *          @OA\Items(
 *              @OA\Property(
 *                  property="id",
 *                  description="Id user",
 *                  type="string",
 *              ),
 *              @OA\Property(
 *                  property="name",
 *                  description="Имя пользователя",
 *                  type="string",
 *              ),
 *              @OA\Property(
 *                  property="email",
 *                  description="Почта пользователя",
 *                  type="integer",
 *              ),
 *              @OA\Property(
 *                  property="code",
 *                  description="Код из сообщения для подтверждения",
 *                  type="integer",
 *              ),
 *              @OA\Property(
 *                  property="phone",
 *                  description="Телефон пользователя",
 *                  type="bigInteger",
 *              ),
 *              @OA\Property(
 *                  property="email_verified_at",
 *                  description="Время подтверждения почты",
 *                  type="string",
 *              ),
 *              @OA\Property(
 *                  property="password",
 *                  description="Пароль пользователя",
 *                  type="string",
 *              ),
 *              @OA\Property(
 *                  property="phone_confirmed",
 *                  description="За что была произведена оплата",
 *                  type="bool",
 *              ),
 *              @OA\Property(
 *                  property="role_index",
 *                  description="Индекс роли пользователя",
 *                  type="integer",
 *              ),
 *              @OA\Property(
 *                  property="hash",
 *                  description="Хэш пользователя",
 *                  type="integer",
 *              ),
 *              @OA\Property(
 *                  property="created_at",
 *                  description="Дата создания пользователя",
 *                  type="integer",
 *              ),
 *              @OA\Property(
 *                  property="updated_at",
 *                  description="Дата обновления данных пользователя",
 *                  type="integer",
 *              ),
 *              @OA\Property(
 *                  property="locale",
 *                  description="Расположение пользователя при регистрации",
 *                  type="integer",
 *              ),
 *              @OA\Property(
 *                  property="api_token",
 *                  description="Api токен пользователя",
 *                  type="integer",
 *              ),
 *          ),
 *     ),
 *     @OA\Property(
 *          property="first_page_url",
 *          description="Ссылка на первую страницу списка",
 *          type="string",
 *     ),
 *     @OA\Property(
 *          property="from",
 *          description="Номер первого элемента списка на странице",
 *          type="integer",
 *     ),
 *     @OA\Property(
 *          property="last_page",
 *          description="Номер последней страницы списка",
 *          type="integer",
 *     ),
 *     @OA\Property(
 *          property="last_page_url",
 *          description="Ссылка на последнюю страницу списка",
 *          type="string",
 *     ),
 *     @OA\Property(
 *          property="links",
 *          type="array",
 *          @OA\Items(
 *              @OA\Property(
 *                  property="url",
 *                  description="Url страницы",
 *                  type="string",
 *              ),
 *              @OA\Property(
 *                  property="label",
 *                  description="Подпись к ссылке",
 *                  type="string",
 *              ),
 *              @OA\Property(
 *                  property="active",
 *                  description="Является ли страница текущей?",
 *                  type="bool",
 *              ),
 *          ),
 *     ),
 *     @OA\Property(
 *          property="next_page_url",
 *          description="Ссылка на следующую страницу списка",
 *          type="string",
 *     ),
 *     @OA\Property(
 *          property="path",
 *          description="Путь из адресной строки",
 *          type="string",
 *     ),
 *     @OA\Property(
 *          property="per_page",
 *          description="Кол-во элементов списка на страницу",
 *          type="integer",
 *     ),
 *     @OA\Property(
 *          property="prev_page_url",
 *          description="Ссылка на предыдущую страницу списка",
 *          type="string",
 *     ),
 *     @OA\Property(
 *          property="to",
 *          description="Номер последнего элемента списка на странице",
 *          type="integer",
 *     ),
 *     @OA\Property(
 *          property="total",
 *          description="Кол-во элементов списка",
 *          type="integer",
 *     ),
 * )
 */
class UserResource {}