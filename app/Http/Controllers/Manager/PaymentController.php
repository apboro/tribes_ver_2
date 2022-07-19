<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Manager\Filters\PaymentsFilter;
use App\Http\Requests\Manager\PaymentsRequest;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Resources\Manager\PaymentResource;

class PaymentController extends Controller
{
    public function list(PaymentsRequest $request, PaymentsFilter $filter)
    {
        $payments = Payment::filter($filter)->paginate($request->get('entries'));
        $payments->load('community');

        return PaymentResource::collection($payments);
    }
}
