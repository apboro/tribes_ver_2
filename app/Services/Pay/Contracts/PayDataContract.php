<?php

namespace App\Services\Pay;


/**
 *  ValueObject объект хранения данных по платежу
 *  должен реализовать интерфейс получения данных о платеже(для репозитория PaySystemContract)
 *  должен хранить все сопутствующие данные для создания обновления удаления платежа
 *  должен хранить результат обработки банком данного платежа
 */
interface PayDataContract
{
    public function getDataForInitPay();
}