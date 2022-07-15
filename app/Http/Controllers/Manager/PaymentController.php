<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Manager\Filters\PaymentsFilter;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function list(Request $request, PaymentsFilter $filter)
    {
//        dd($request);

        $payments = Payment::filter($filter)->paginate($request->get('entries'));

        return $payments;
    }
}
