<?php

namespace App\Services\Pay;
use App\Models\Accumulation;
use App\Models\Course;
use App\Models\DonateVariant;
use App\Models\TariffVariant;
use App\Models\User;
use App\Services\Pay\Entity\Pay;

/**
 * Фабрика для build-методов для создания объектов Pay
 * для всех возможных вариантов платежа
 */
class PayBuilder
{
    public function buildPayForTariff(TariffVariant $tariffVariant,int $amount): Pay
    {
        return new Pay();
    }

    public function buildPayForCourse(Course $course,int $amount): Pay
    {
        return new Pay();
    }

    public function buildPayForDonate(DonateVariant $donateVariant,int $amount): Pay
    {
        return new Pay();
    }

    public function buildPayForAuthor(User $author,Accumulation $accumulation,int $amount): Pay
    {
        return new Pay();
    }
}