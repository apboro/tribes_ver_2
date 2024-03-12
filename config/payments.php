<?php
return [
    'findBank' => [
        'default' => 'App\Services\Tinkoff\Payment',
        'App\Models\Market\ShopOrder' => 'App\Services\Unitpay\Payment'
    ]
];
