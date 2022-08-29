<?php

namespace App\Http\Requests\Swagger\Manager;

/**
 * @OA\Schema(
 *     title="PaymentRequest",
 *     description="Payment filters",
 *      @OA\Property(
 *          property="search",
 *          description="Поиск по номеру оплаты и имени покупателя",
 *          format="string",
 *          example="gsGU4389gGHIU"
 *      ),
 *      @OA\Property(
 *          property="date",
 *          description="Поиск по дате",
 *          format="string",
 *          example="25.07.2022"
 *      ),
 *      @OA\Property(
 *          property="sort",
 *          description="Сортировка списка платежей",
 *          format="object",
 *          @OA\Property(
 *              property="name",
 *              description="Название столбца для сортировка",
 *              format="string",
 *              example="user"
 *          ),
 *          @OA\Property(
 *              property="rule",
 *              description="Сортировка по возрастанию или убыванию",
 *              format="string",
 *              example="default"
 *          ),
 *      ),
 *      @OA\Property(
 *          property="entries",
 *          description="Количество записей на странице",
 *          format="integer",
 *          example=2
 *      ),
 *      @OA\Property(
 *          property="from",
 *          description="Поиск платежей по определенному покупателю",
 *          format="integer",
 *          example=1
 *      ),
 * )
 */
class PaymentRequest {}
