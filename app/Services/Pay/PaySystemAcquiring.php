<?php

namespace App\Services\Pay;

use Illuminate\Support\Facades\Log;

abstract class PaySystemAcquiring
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
    protected $telegram_id;
    protected $accumulation;
    protected bool $recurrent = false;
    protected $charged;
    protected $successUrl;
    protected $failUrl;

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

    public function setTelegramId(?int $telegramId): self
    {
        $this->telegram_id = $telegramId;

        return $this;
    }

    public function setAccumulation($accumulation): self
    {
        $this->accumulation = $accumulation;

        return $this;
    }

    public function setRecurrent(bool $state = false): self
    {
        $this->recurrent = (bool)$state;

        return $this;
    }

    public function setCharged(bool $charged = false): self
    {
        $this->charged = $charged;

        return $this;
    }

    public function setSuccessUrl(?string $url): self
    {
        $this->successUrl = $url;

        return $this;
    }

    public function setFailUrl(?string $url): self
    {
        $this->failUrl = $url;

        return $this;
    }

}