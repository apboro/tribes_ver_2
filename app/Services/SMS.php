<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use stdClass;
use App\Models\SmsConfirmations as SmsConfirmation;

class SMS {

    private $ApiKey;
    private $protocol = 'https';
    private $domain = 'sms.ru';
    private $count_repeat = 5;    //количество попыток достучаться до сервера если он не доступен

    function __construct() {
        $this->ApiKey = env('SMS_RU_CODE', null);
    }

    public static function smsConfirmed($request)
    {
        $sms = SmsConfirmation::where('ip', $request->ip())->where('phone', $request['phone'])->first();
        if($sms != null && $sms->confirmed){
            return true;
        } else {
            return false;
        }
    }

    public static function isSmsBlocked($request){
        $sms = SmsConfirmation::where('ip', $request->ip())->where('phone', $request['phone'])->first();
        if($sms != null && $sms->isblocked){
            return true;
        } else {
            return false;
        }
    }

    public function send_one($post) {
        $url = $this->protocol . '://' . $this->domain . '/sms/send';
        $request = $this->Request($url, $post);
        $resp = $this->CheckReplyError($request, 'send');

        if ($resp->status == "OK") {
            $temp = (array) $resp->sms;
            unset($resp->sms);

            $temp = array_pop($temp);

            if ($temp) {
                return $temp;
            } else {
                return $resp;
            }
        }
        else {
            return $resp;
        }

    }

    public function phoneSendOne($post) {
        $url = $this->protocol . '://' . $this->domain . '/code/call';
        $request = $this->Request($url, $post);
        $resp = $this->CheckReplyError($request, 'send');
        return $resp;

    }

    public function send($post) {
        $url = $this->protocol . '://' . $this->domain . '/sms/send';
        $request = $this->Request($url, $post);
        return $this->CheckReplyError($request, 'send');
    }

    public function phoneSend($post) 
    {
        $url = $this->protocol . '://' . $this->domain . '/code/call';
        $request = $this->Request($url, $post);
        return $this->CheckReplyError($request, 'send');
    }

    public function sendSmtp($post) {
        $post->to = $this->ApiKey . '@' . $this->domain;
        $post->subject = $this->sms_mime_header_encode($post->subject, $post->charset, $post->send_charset);
        if ($post->charset != $post->send_charset) {
            $post->body = iconv($post->charset, $post->send_charset, $post->body);
        }
        $headers = "From: $post->\r\n";
        $headers .= "Content-type: text/plain; charset=$post->send_charset\r\n";
        return mail($post->to, $post->subject, $post->body, $headers);
    }

    public function getStatus($id) {
        $url = $this->protocol . '://' . $this->domain . '/sms/status';

        $post = new stdClass();
        $post->sms_id = $id;

        $request = $this->Request($url, $post);
        return $this->CheckReplyError($request, 'getStatus');
    }

    public function getCost($post) {
        $url = $this->protocol . '://' . $this->domain . '/sms/cost';
        $request = $this->Request($url, $post);
        return $this->CheckReplyError($request, 'getCost');
    }

    /**
     * Получение состояния баланса
     */
    public function getBalance() {
        $url = $this->protocol . '://' . $this->domain . '/my/balance';
        $request = $this->Request($url);
        return $this->CheckReplyError($request, 'getBalance');
    }

    /**
     * Получение текущего состояния вашего дневного лимита.
     */
    public function getLimit() {
        $url = $this->protocol . '://' . $this->domain . '/my/limit';
        $request = $this->Request($url);
        return $this->CheckReplyError($request, 'getLimit');
    }

    /**
     * Получение списка отправителей
     */
    public function getSenders() {
        $url = $this->protocol . '://' . $this->domain . '/my/senders';
        $request = $this->Request($url);
        return $this->CheckReplyError($request, 'getSenders');
    }

    /**
     * Проверка номера телефона и пароля на действительность.
     * @param $post
     *   $post->login = string - номер телефона
     *   $post->password = string - пароль
     * @return mixed|\stdClass
     */
    public function authCheck($post) {
        $url = $this->protocol . '://' . $this->domain . '/auth/check';
        $post->api_id = 'none';
        return $this->CheckReplyError($request, 'AuthCheck');
    }


    /**
     * На номера, добавленные в стоплист, не доставляются сообщения (и за них не списываются деньги)
     * @param string $phone Номер телефона.
     * @param string $text Примечание (доступно только вам).
     * @return mixed|\stdClass
     */
    public function addStopList($phone, $text = "") {
        $url = $this->protocol . '://' . $this->domain . '/stoplist/add';

        $post = new stdClass();
        $post->stoplist_phone = $phone;
        $post->stoplist_text = $text;

        $request = $this->Request($url, $post);
        return $this->CheckReplyError($request, 'addStopList');
    }

    /**
     * Удаляет один номер из стоплиста
     * @param string $phone Номер телефона.
     * @return mixed|\stdClass
     */
    public function delStopList($phone) {
        $url = $this->protocol . '://' . $this->domain . '/stoplist/del';

        $post = new stdClass();
        $post->stoplist_phone = $phone;

        $request = $this->Request($url, $post);
        return $this->CheckReplyError($request, 'delStopList');
    }

    /**
     * Получить номера занесённые в стоплист
     */
    public function getStopList() {
        $url = $this->protocol . '://' . $this->domain . '/stoplist/get';
        $request = $this->Request($url);
        return $this->CheckReplyError($request, 'getStopList');
    }

    /**
     * Позволяет отправлять СМС сообщения, переданные через XML компании UCS, которая создала ПО R-Keeper CRM (RKeeper). Вам достаточно указать адрес ниже в качестве адреса шлюза и сообщения будут доставляться автоматически.
     */
    public function ucsSms() {
        $url = $this->protocol . '://' . $this->domain . '/ucs/sms';
        $request = $this->Request($url);
        $output->status = "OK";
        $output->status_code = '100';
        return $output;
    }

    /**
     * Добавить URL Callback системы на вашей стороне, на которую будут возвращаться статусы отправленных вами сообщений
     * @param $post
     *    $post->url = string - Адрес обработчика (должен начинаться на http://)
     * @return mixed|\stdClass
     */
    public function addCallback($post) {
        $url = $this->protocol . '://' . $this->domain . '/callback/add';
        $request = $this->Request($url, $post);
        return $this->CheckReplyError($request, 'addCallback');
    }

    /**
     * Удалить обработчик, внесенный вами ранее
     * @param $post
     *   $post->url = string - Адрес обработчика (должен начинаться на http://)
     * @return mixed|\stdClass
     */
    public function delCallback($post) {
        $url = $this->protocol . '://' . $this->domain . '/callback/del';
        $request = $this->Request($url, $post);
        return $this->CheckReplyError($request, 'delCallback');
    }

    /**
     * Все имеющиеся у вас обработчики
     */
    public function getCallback() {
        $url = $this->protocol . '://' . $this->domain . '/callback/get';
        $request = $this->Request($url);
        return $this->CheckReplyError($request, 'getCallback');
    }

    private function Request($url, $post = FALSE) {
        if ($post) {
            $r_post = $post;
        }
        $ch = curl_init($url . "?json=1");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        if (!$post) {
            $post = new stdClass();
        }

        if (!empty($post->api_id) && $post->api_id=='none'){
        }
        else {
            $post->api_id = $this->ApiKey;
        }

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query((array) $post));

        $body = curl_exec($ch);
        if ($body === FALSE) {
            $error = curl_error($ch);
        }
        else {
            $error = FALSE;
        }
        curl_close($ch);
        if ($error && $this->count_repeat > 0) {
            $this->count_repeat--;
            return $this->Request($url, $r_post);
        }
        return $body;
    }

    private function CheckReplyError($res, $action) {

        if (!$res) {
            $temp = new stdClass();
            $temp->status = "ERROR";
            $temp->status_code = "000";
            $temp->status_text = "Невозможно установить связь с сервером.";
            return $temp;
        }

        $result = json_decode($res);

        if (!$result || !$result->status) {
            $temp = new stdClass();
            $temp->status = "ERROR";
            $temp->status_code = "000";
            $temp->status_text = "Невозможно установить связь с сервером.";
            return $temp;
        }

        return $result;
    }

    private function sms_mime_header_encode($str, $post_charset, $send_charset) {
        if ($post_charset != $send_charset) {
            $str = iconv($post_charset, $send_charset, $str);
        }
        return "=?" . $send_charset . "?B?" . base64_encode($str) . "?=";
    }
}
