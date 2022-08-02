<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Manager\Filters\PaymentsFilter;
use App\Http\Requests\Manager\PaymentsRequest;
use App\Http\Resources\Manager\CustomersResource;
use App\Models\Payment;
use App\Http\Resources\Manager\PaymentResource;

class PaymentController extends Controller
{
    /**
     * @OA\Post(
     *     path="/v2/payments",
     *     tags={"PaymentController"},
     *     summary="Get list payments",
     *     operationId="getListPayments",
     *     security={{"sanctum": {} }},
     *     @OA\RequestBody(
     *         required=false,
     *         description="Фильтры платежей",
     *         @OA\JsonContent(
     *              ref="#/components/schemas/PaymentRequest"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfuly get list payments",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                  property="payments",
     *                  type="object",
     *                  ref="#/components/schemas/PaymentResource",
     *              ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Redirect to main page, if user is not admin"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=419,
     *         description="Page expired",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="The given data was invalid",
     *     ),
     * )
     */

    public function list(PaymentsRequest $request, PaymentsFilter $filter)
    {
        $payments = Payment::filter($filter)->paginate($request->get('entries'));
        $payments->load('community');

        return PaymentResource::collection($payments);
    }


    /**
     * @OA\Post(
     *     path="/v2/customers",
     *     tags={"PaymentController"},
     *     summary="Get list unique buyers",
     *     operationId="getListUniqueBuyers",
     *     @OA\Response(
     *         response=200,
     *         description="Successfuly get list payments",
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Redirect to main page, if user is not admin"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=419,
     *         description="Page expired",
     *     ),
     *     security={{"sanctum": {} }}
     * )
     */
    public function customers()
    {
        $customers = Payment::all()->unique('user_id');

        return new CustomersResource($customers->sortBy('from'));
    }
}