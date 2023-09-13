<?php

namespace App\Http\Middleware;

use App\Domain\Entity\User\Service\SubscriptionService;
use App\Exceptions\ApiUnauthorizedException;
use App\Http\ApiResponses\ApiResponse;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthenticateApiV3 extends Middleware
{
    private SubscriptionService $subscriptionService;

    public function __construct(Auth $auth, SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
        parent::__construct($auth);
    }

    /**
     * @inheritdoc
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        if ($this->subscriptionService->isExpiredDate($request->user())){
            log::info('Expired Date');
            return ApiResponse::forbidden('subscription.expired_data');
        }

        return $next($request);
    }


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
