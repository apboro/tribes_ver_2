<?php

namespace App\Listeners;

use App\Events\ApiUserRegister;
use App\Http\ApiResponses\ApiResponse;
use App\Http\ApiResponses\ApiResponseError;
use App\Jobs\SendEmails;
use Exception;
use Log;

class UserRegisterSendEmail
{
    /**
     * Handle the event.
     *
     * @param ApiUserRegister $event
     *
     * @return ApiResponseError
     */
    public function handle(ApiUserRegister $event)
    {
        $v = view('mail.registration')->with(['login' => $event->user->email, 'password' => $event->password])->render();

        try {
            SendEmails::dispatch($event->user->email, 'Регистрация', 'Сервис ' . env('APP_NAME'), $v);
        } catch (Exception $e) {
            log::error('UserRegisterSendEmail handler: ' . json_encode($e, JSON_UNESCAPED_UNICODE));
            return ApiResponse::error($e->getMessage());
        }
    }
}
