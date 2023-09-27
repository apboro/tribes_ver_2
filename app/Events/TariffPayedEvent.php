<?php

namespace App\Events;

use App\Models\Tariff;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Foundation\Events\Dispatchable;

class TariffPayedEvent
{
    use Dispatchable;

    public Payment $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }
}
