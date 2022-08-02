<?php

namespace App\Services\Pay;

use App\Services\Pay\Contracts\PaySystemContract;
use App\Services\Pay\Entity\Pay;

class PayProcessor
{
    private PaySystemContract $payRepository;
    private PaymentRepository $paymentRepository;

    public function __construct(
        PaySystemContract $payRepository,
        PaymentRepository $paymentRepository
    )
    {
        $this->payRepository = $payRepository;
        $this->paymentRepository = $paymentRepository;
    }

    public function procCreatePayForAuthor(Pay $pay)
    {
        //todo вывод автором заработанных средств
    }

    public function procCreatePayForSite(Pay $pay)
    {
        //todo списание средств заработанных сайтом(маржа)
    }

    public function procAbortPay(Pay $pay)
    {
      // в результате каких то коллизий, надо вернуть деньги покупателю из копилки
    }

    public function procUnsubscribePay(Pay $pay)
    {
        //todo отказаться от подписки по периодическому платежу
    }

    public function procUpdatePay(Pay $pay)
    {
        //todo обновить платеж согласно полученным данным(от банка изменения статусов)
    }

    public function procCreatePayForPayer(Pay $pay)
    {
        // оплата всего что угодно на сайте покупателями()
        // зачисление средств в копилку автору
    }
}