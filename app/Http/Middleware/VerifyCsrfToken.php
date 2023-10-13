<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'bot/*',
        'community/*',
        'test/',
        '/payment/donate/range',
        '/tinkoff/notify',
        '/webhook-user-bot',
        '/api/*',
        '/telegram-bot-integration/webhook',
        '/wbnr/webhook'
    ];
}
