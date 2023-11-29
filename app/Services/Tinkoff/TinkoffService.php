<?php
namespace App\Services\Tinkoff;

use App\Services\Tinkoff\TinkoffApi;

class TinkoffService
{
    public TinkoffApi $payTerminal;
    public TinkoffApi $e2cTerminal;
    public TinkoffApi $directTerminal;

    public function __construct()
    {
        // todo в контейнер
        $this->payTerminal = new TinkoffApi(config('tinkoff.terminals.terminalKey'), config('tinkoff.terminals.secretKey'));
        $this->e2cTerminal = new TinkoffApi(config('tinkoff.terminals.terminalKeyE2C'), config('tinkoff.terminals.secretKeyE2C'));
        $this->directTerminal = new TinkoffApi(config('tinkoff.terminals.terminalDirect'), config('tinkoff.terminals.terminalDirectSecretKey'));
    }

    public function initPay($args)
    {
        return $this->payTerminal->init($args);
    }

    public function initDirectPay($args)
    {
        return $this->directTerminal->init($args);
    }

}