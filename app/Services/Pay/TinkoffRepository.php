<?php

namespace App\Services\Pay;

use App\Services\Pay\Contracts\PaySystemContract;
use App\Services\Pay\Entity\Pay;
use App\Services\Tinkoff\TinkoffApi;

class TinkoffRepository implements PaySystemContract
{

    public function __construct(

    )
    {
        $this->payTerminal = app('payTerminal');
        $this->e2cTerminal = app('e2cTerminal');
    }

    public function abortPayment(Pay $pay)
    {
        // TODO: Implement abortPayment() method.
    }

    public function chargePayment(Pay $pay)
    {
        // TODO: Implement chargePayment() method.
    }

    public function initPayment(PayDataContract $pay)
    {
        // TODO: Implement initPayment() method.
    }

    public function isHasAbortPayment(Pay $pay)
    {
        // TODO: Implement isHasAbortPayment() method.
    }

    public function checkPayment(Pay $pay)
    {
        // TODO: Implement checkPayment() method.
    }
}