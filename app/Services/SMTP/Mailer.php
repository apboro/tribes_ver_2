<?php

namespace App\Services\SMTP;

use App\Services\TelegramLogService;
use Illuminate\Support\Facades\Log;

class Mailer
{
    public function __construct($from, $html, $subject, $to)
    {
        $err = $this->send($subject, $from, $html, $to);

        if ($err) {
            Log::error('Ошибка отправки SMTP на почту ' . $to . ' с темой ' . $subject . ' Ответ сервера: ' . $err);
        } else {
            Log::info('Успешная отправка SMTP на почту ' . $to . ' с темой ' . $subject);
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
        if (env('APP_ENV') !== 'testing') {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => env('MAIL_SMTP_URL'),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Basic ".base64_encode('api:'.env('MAIL_SMTP_API_KEY')),
                ),
                CURLOPT_POSTFIELDS => http_build_query([
                        'subject' => $subject, // Обязательно
                        'from' => $from . '<'.env('MAIL_FROM_ADDRESS').'>'?? 'Сервис Spodial <'.env('MAIL_FROM_ADDRESS').'>', // Обязательно
                        'html' => $html, // Обязательно
                        'to' => $to, // Обязательно
                ])
            ));

            $response = curl_exec($curl);

            $err = curl_error($curl);

            curl_close($curl);

            return $err;
        } else {
            Log::debug('send email', [
                'subject' => $subject, // Обязательно
                'from' => 'Сервис Spodial <'.env('MAIL_FROM_ADDRESS').'>', // Обязательно
                'html' => $html, // Обязательно
                'to' => $to, // Обязательно
            ]);
            return false;
        }
    }


}