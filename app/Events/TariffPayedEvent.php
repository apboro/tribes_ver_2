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
    public Tariff $tariff;
    public Payment $payment;

    public function __construct(User $user, Tariff $tariff, Payment $payment)
    {
        $this->user = $user;
        $this->tariff = $tariff;
        $this->payment = $payment;
    }
}
