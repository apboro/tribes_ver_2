<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SMS16
{
    private $ApiKey;
    private $login;
    private $params = [];
    private $query = [];
    private $protocol = 'https://';
    private $domain = 'new.sms16.ru/get/';
    private $timestemp;
    private $error = [
        '000' => 'Сервис отключён',
        '1' => 'Не указанна подпись',
        '2' => 'Не указан логин',
        '3' => 'Не указан текст',
        '4' => 'Не указан телефон',
        '5' => 'Не указан отправитель',
        '6' => 'Не корректная подпись',
        '7' => 'Не корректный логин',
        '8' => 'Не корректное имя отправителя',
        '9' => 'Не зарегистрированное имя отправителя',
        '12' => 'Ошибка отправки СМС',
        '13' => 'Номер находится в стоп-листе. Отправка на этот номер запрещена',
        '16' => 'Не корректный номер'
    ];


    function __construct()
    {
        $this->ApiKey = config('services.sms16.token', null);
        $this->login = config('services.sms16.login', NULL);
    }

    // Добавить номер в стоп лист
    public function AddStopList($phone)
    {
        $this->params ['phone'] = $phone;
        $this->query ['phone'] = $phone;

        return $this->Request('add2stop.php');
    }

    // Проверка баланса
    public function getBalance()
    {
        return $this->Request('balance.php');
    }

    // Отправить сообщение
    public function sendMessage($phone, $text)
    {
        $this->params ['phone'] = $phone;
        $this->params ['text'] = $text;
        $this->params['sender'] = 'mytestsms';

        $this->query ['phone'] = $phone;
        $this->query ['text'] = $text;
        $this->query ['sender'] = 'mytestsms';

        return $this->Request('send.php');
    }

    function Request($destination)
    {
        $this->getTimestamp();
        $this->params ['login'] = $this->login;
        $this->params ['timestamp'] = $this->timestemp;
        
        $this->query ['login'] = $this->login;
        $this->query ['signature'] = $this->signature();
        $this->query ['timestamp'] = $this->timestemp;

        $request = Http::get($this->protocol . $this->domain . $destination, $this->query);

        if (isset($request->json()['error'])) {
            return $this->error[$request->json()['error']];
        } else return $request->json();
        
    }

    function getTimestamp()
    {
        $timestemp = Http::get($this->protocol . $this->domain . 'timestamp.php');
        $this->timestemp = $timestemp->json();
    }

    function signature()
    {
        ksort($this->params);
        reset($this->params);

        return md5(implode($this->params) . $this->ApiKey);
    }
}
