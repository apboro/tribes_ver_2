<?php

namespace App\Services\Tinkoff;

use Illuminate\Support\Facades\Log;

abstract class Acquiring
{
    public $amount = 0; // Сумма в копейках
    public $payer;

    public $type;
    public $payFor;
    public $orderId;

    protected $serviceName;
    protected $email;
    protected $phone;
    protected $quantity;

    abstract public function run();

    public static function create(): self
    {
        return new static();
    }

    public function setAmount(?int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function payFor($payFor): self
    {
        $this->payFor = $payFor;

        return $this;
    }

    public function setPayer($payer): self
    {
        $this->payer = $payer;

        return $this;
    }

    public function setOrderId(?string $orderId): self
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function setServiceName(?string $serviceName): self
    {
        $this->serviceName = $serviceName;

        return $this;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}