<?php

namespace App\Services\Pay\Entity;

use App\Services\Pay\PayDataContract;

/**
 *  ValueObject объект хранения данных по платежу
 *  должен реализовать интерфейс получения данных о платеже(для репозитория PaySystemContract)
 *  должен хранить все сопутствующие данные для создания обновления удаления платежа
 *  должен хранить результат обработки банком данного платежа
 */
class Pay implements PayDataContract
{
    public function getDataForInitPay()
    {

    }
}