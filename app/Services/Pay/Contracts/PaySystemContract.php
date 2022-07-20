<?php

namespace App\Services\Pay\Contracts;

use App\Services\Pay\Entity\Pay;
use App\Services\Pay\PayDataContract;

interface PaySystemContract
{
    /**
     * Обращение к банку на создание нового платежа
     * @return mixed
     */
    public function initPayment(PayDataContract $pay);

    /**
     * Обращение к банку на отмену текущего платежа
     * @return mixed
     */
    public function abortPayment(Pay $pay);

    /**
     * Обращение к банку на авто обновление платежа(периодические платежи по подписке)
     * @param Pay $pay
     * @return mixed
     */
    public function chargePayment(Pay $pay);

    /**
     * Проверить сможем ли мы отменить платеж
     * @param Pay $pay
     * @return mixed
     */
    public function isHasAbortPayment(Pay $pay);

    /**
     * Проверка смены статуса платежа
     * @param Pay $pay
     * @return mixed
     */
    public function checkPayment(Pay $pay);
}