<?php

namespace App\Events;

use App\Models\Tariff;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Foundation\Events\Dispatchable;

class TariffPayedEvent
{
    use Dispatchable;

    public User $user;
    public Payment $payment;

    public function __construct(User $user, Payment $payment)
    {
        $this->user = $user;
        $this->payment = $payment;
    }
}
