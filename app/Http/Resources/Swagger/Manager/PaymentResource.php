<?php

namespace App\Http\Resources\Swagger\Manager;

/**
* @OA\Schema(
 *     title="PaymentResource",
 *     description="Payment response",
 *     @OA\Xml(name="payments"),
 *     @OA\Property(
 *          property="data",
 *          type="array",
 *          collectionFormat="multi",
 *          @OA\Items(
 *              @OA\Property(
 *                  property="OrderId",
 *                  description="Id order",
 *                  type="string",
 *              ),
 *              @OA\Property(
 *                  property="community",
 *                  description="Community's name",
 *                  type="string",
 *              ),
 *              @OA\Property(
 *                  property="add_balance",
 *                  description="Сумма прибавления",
 *                  type="integer",
 *              ),
 *              @OA\Property(
 *                  property="from",
 *                  description="ФИО покупателя",
 *                  type="string",
 *              ),
 *              @OA\Property(
 *                  property="status",
 *                  description="Статус платежа",
 *                  type="string",
 *              ),
 *              @OA\Property(
 *                  property="created_at",
 *                  description="Дата создания платежа",
 *                  type="string",
 *              ),
 *              @OA\Property(
 *                  property="type",
 *                  description="За что была произведена оплата",
 *                  type="integer",
 *              ),
 *          ),
 *     ),
 *     @OA\Property(
 *          property="links",
 *          type="object",
 *          collectionFormat="multi",
 *              @OA\Property(
 *                  property="first",
 *                  description="Ссылка на первую страницу списка",
 *                  type="string",
 *              ),
 *              @OA\Property(
 *                  property="last",
 *                  description="Ссылка на последнюю страницу списка",
 *                  type="string",
 *              ),
 *              @OA\Property(
 *                  property="prev",
 *                  description="Ссылка на предыдущую страницу списка",
 *                  type="string",
 *              ),
 *              @OA\Property(
 *                  property="next",
 *                  description="Ссылка на следующую страницу списка",
 *                  type="string",
 *              ),
 *       ),
 *     @OA\Property(
 *          property="meta",
 *          type="object",
 *              @OA\Property(
 *                  property="current_page",
 *                  description="Номер текущей страницы списка",
 *                  type="integer",
 *              ),
 *              @OA\Property(
 *                  property="from",
 *                  description="Номер первого элемента списка на текущей странице",
 *                  type="integer",
 *              ),
 *              @OA\Property(
 *                  property="last_page",
 *                  description="Номер последней страницы списка списка",
 *                  type="integer",
 *              ),
 *              @OA\Property(
 *                  property="links",
 *                  type="array",
 *                  @OA\Items(
 *                       @OA\Property(
 *                       property="url",
 *                       description="Url страницы",
 *                       type="string",
 *                       ),
 *                       @OA\Property(
 *                       property="label",
 *                       description="Подпись к ссылке",
 *                       type="string",
 *                       ),
 *                       @OA\Property(
 *                       property="active",
 *                       description="Является ли страница текущей?",
 *                       type="bool",
 *                       ),
 *                  ),
 *              ),
 *              @OA\Property(
 *                  property="path",
 *                  description="Путь страницы",
 *                  type="string",
 *              ),
 *              @OA\Property(
 *                  property="per_page",
 *                  description="Количество элементов списка на страницу",
 *                  type="integer",
 *              ),
 *              @OA\Property(
 *                  property="to",
 *                  description="Номер последнего элемента списка на текущей странице",
 *                  type="integer",
 *              ),
 *              @OA\Property(
 *                  property="total",
 *                  description="Всего элементов в списке",
 *                  type="integer",
 *              ),
 *       ),
 * )
 */
class PaymentResource {}