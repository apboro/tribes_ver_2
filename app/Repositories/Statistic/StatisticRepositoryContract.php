<?php

namespace App\Repositories\Statistic;

interface StatisticRepositoryContract
{
    /** Получение уникальных посетителей платёжной страницы */
    public function getHosts();

    /** Получение количества оплативших подписчиков */
    public function getPaidSubscribers();

    /** Получение всех подписчиков */
    public function getAllSubscribers();

    /** Получение уникальных посетителей платёжной страницы за период времени в формате Y-m-d*/
    public function getHostsPeriod($fromTime, $beforeTime);
    
    /** Получение просмотров платёжной страницы */
    public function getViews();

    /** Получение просмотров платёжной страницы за период времени в формате Y-m-d*/
    public function getViewsPeriod($fromTime, $beforeTime); 

    /** Получение суммы донатов за весь период */
    public function getDonateSum();

    /** Получение суммы тарифов за весь период */
    public function getTariffSum();
    
    /** Получение суммы донатов за определенный период времени. Время в формате Y-m-d H:i:s */
    public function getDonateSumPeriod($fromTime, $beforeTime);
    
    /** Получение суммы тарифов за определенный период времени. Время в формате Y-m-d H:i:s */
    public function getTariffSumPeriod($fromTime, $beforeTime);
    
     /** Получение количества донатов за весь период */
     public function getTotalDonate();
     
     /** Получение количества тарифов за весь период */
    public function getTotalTariff();
    
    /** Получение количества донатов за определенный период времени. Время в формате Y-m-d H:i:s */
    public function getTotalDonatePeriod($fromTime, $beforeTime);
    
     /** Получение количества тарифов за определенный период времени. Время в формате Y-m-d H:i:s */
     public function getTotalTariffPeriod($fromTime, $beforeTime);
     
}
