<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiUnauthorizedException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class AuthenticateApiV3 extends Middleware
{
    /**
     * Handle an unauthenticated user.
     *
     * @param Request $request
     * @param array $guards
     *
     * @return void
     *
     * @throws ApiUnauthorizedException
     */
    protected function unauthenticated($request, array $guards): void
    {
        throw new ApiUnauthorizedException('Unauthenticated.');
    }
}
