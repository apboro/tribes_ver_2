<?php

namespace App\Services\SMTP;

use App\Services\TelegramLogService;
use Illuminate\Support\Facades\Log;

class Mailer
{
    public function __construct($from, $html, $subject, $to)
    {
        $err = $this->send($subject, $from, $html, $to);

        //FALLS with Bad Request: group chat was upgraded to a supergroup chat, switch off now
        if ($err) {
            TelegramLogService::staticSendLogMessage('Ошибка отправки SMTP на почту ' . $to . ' с темой ' . $subject . ' Ответ сервера: ' . $err );
        } else {
            TelegramLogService::staticSendLogMessage('Успешная отправка SMTP на почту ' . $to . ' с темой ' . $subject);

        }
    }

    /**
     * @param $subject
     * @param $from
     * @param $html
     * @param $to
     * @return string
     */
    public function send($subject, $from, $html, $to): string
    {
        if(env('APP_ENV') !== 'testing') {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => env('MAIL_SMTP_URL'),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_HTTPHEADER => array(
                    "authorization: ".env('MAIL_SMTP_API_KEY'),
                ),
                CURLOPT_POSTFIELDS => http_build_query([
                    'subject' => $subject, // Обязательно
                    'name' => $from,
                    'html' => $html, // Обязательно
                    'from' => env('MAIL_FROM_ADDRESS', 'no-reply@example.com'), // Обязательно
                    'to' => $to, // Обязательно
                    'headers' => "[{ 'msg-type': 'media' }]",
                    'text' => "ТЕСТ"
                ])
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            return $err;
        } else {
            Log::debug('send email',[
                'subject' => $subject, // Обязательно
                'name' => $from,
                'html' => $html, // Обязательно
                'from' => env('MAIL_FROM_ADDRESS', 'no-reply@example.com'), // Обязательно
                'to' => $to, // Обязательно
                'headers' => "[{ 'msg-type': 'media' }]",
                'text' => "ТЕСТ"
            ]);
            return false;
        }
    }


}