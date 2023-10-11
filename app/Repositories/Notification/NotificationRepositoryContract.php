<?php

namespace App\Repositories\Notification;

interface NotificationRepositoryContract
{
    public function send($phone, $message);

    public function tryActivateAccount($user, $code);

    public static function sendConfirmationTo($user, $phoneCode, $phone);
}
