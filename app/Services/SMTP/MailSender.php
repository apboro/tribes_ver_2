<?php

namespace App\Services\SMTP;

use App\Http\ApiRequests\ApiSendEmailRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Jobs\SendEmails;

class MailSender
{
    public function sendDemoEmail(ApiSendEmailRequest $request)
    {
        $html = "<ul><li>Имя: $request->name</li><li>Почта: $request->email</li><li>Телефон: $request->phone</li><li>Сообщение: $request->text</li></ul>";
        SendEmails::dispatch(['info@spodial.com'], 'Запись на демо Spodial', 'Промо Spodial', $html);

        return ApiResponse::success('common.success');
    }

}