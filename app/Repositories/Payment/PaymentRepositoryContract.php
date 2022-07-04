<?php
namespace App\Repositories\Payment;

use App\Filters\PaymentFilter;

interface PaymentRepositoryContract
{
    public function initPayment($data, $community);
    public function getList(PaymentFilter $filters);
    public function getPaymentById($id);
}