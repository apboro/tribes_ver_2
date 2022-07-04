<?php

namespace App\Services\SMTP;

use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;

class Mailer
{
    public function __construct($from, $html, $subject, $to)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.smtp.bz/v1/smtp/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "authorization: h0IH0IBP1HNcQZOTaZbqvkquhCtmNN2VMzsM"
            ),
            CURLOPT_POSTFIELDS => http_build_query([
                'subject' => $subject, // Обязательно
                'name' => $from,
                'html' => $html, // Обязательно
                'from' => "no-reply@spodial.com", // Обязательно
                'to' => $to, // Обязательно
                'headers' => "[{ 'msg-type': 'media' }]",
                'text' => "ТЕСТ"
            ])
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            TelegramLogService::staticSendLogMessage('Ошибка отправки SMTP на почту ' . $to . ' с темой ' . $subject . ' Ответ сервера: ' . $err );
        } else {
            TelegramLogService::staticSendLogMessage('Успешная отправка SMTP на почту ' . $to . ' с темой ' . $subject);
        }
    }


}