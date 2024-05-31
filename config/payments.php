<?php
return [
    'modelUseBank' => [
        'default' => 'App\Services\Tinkoff\Payment',
        // 'App\Models\Market\ShopOrder' => 'App\Services\Unitpay\Payment'
    ],
    'banksList' => [
        'tinkoff' => 'App\Services\Tinkoff\Payment',
        'unitpay' => 'App\Services\Unitpay\Payment',
        'yookassa' => 'App\Services\Yookassa\Payment',
    ]
];
