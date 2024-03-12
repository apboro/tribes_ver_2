<?php

namespace App\Services\Unitpay;

use App\Models\User;

class CashRegister
{
    public static function buildCashCheck(string $type, User $payer, object $payFor): array
    {
        if ($type == 'shopOrder') {
            return self::buildCheckForShopOrder($payer, $payFor);
        }

        return [];
    }

    private static function buildCheckForShopOrder(User $payer, object $payFor): array
    {
        $result = [];

        $result['customerEmail'] = $payer->email; 
        if ($payer->phone) {
            $result['phone'] = $payer->phone; 
        }

        $products = [];
        foreach ($payFor->products as $product) {
            $products[] = ["name" => $product->title, 
                        "count" => $product->pivot->quantity, 
                        "price" => $product->pivot->price, 
                        "type" => "commodity"];
        }
        $result['cashItems'] = base64_encode(json_encode($products));

        return $result;
    }
}