<?php

namespace App\Services\Yookassa;

use App\Models\User;

class CashRegister
{
    public static function buildCashCheck(string $type, User $payer, object $payFor): array
    {
        if ($type == 'shopOrder') {
            return self::buildProductList($payer, $payFor);
        }

        return [];
    }

    private static function buildProductList(User $payer, object $payFor): array
    {
        $products = [];
        foreach ($payFor->products as $product) {
            $products[] =
                [
                    'description' => $product->title,
                    'quantity' => $product->pivot->quantity,
                    'amount' => [
                        'value' => $product->pivot->price,
                        'currency' => 'RUB'
                    ],
                    'vat_code' => '2', // necessary
                    'payment_mode' => 'full_payment',
                    'payment_subject' => 'commodity', 
                ];
        }

        return $products;
    }
}