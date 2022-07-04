<?php

namespace App\Services;

use stdClass;

class Webcaster
{
    private $domain = 'api.webcaster.pro';
    private $contentType = '.json';
    private $protocol = 'https';
    private $secret;
    private $cid;

    function __construct($cid, $secret) {
        $this->cid = $cid;
        $this->secret = $secret;
    }

    // Информация о сервисах
    public function getServices($post = []) {
        $url = $this->protocol . '://' . $this->domain . '/api/services' . $this->contentType;
        $request = $this->Request($url, $post);
        $resp = json_decode($request);

        return $resp;
    }

    // Информация о скриншотах видео
    public function getThumb($post = []) {
        $url = $this->protocol . '://' . $this->domain . '/api/thumbnails' . $this->contentType;
        $request = $this->Request($url, $post);
        $resp = json_decode($request);

        return $resp;
    }

    // Установка основного скриншота видео
    public function setThumb($post = []) {
        $url = $this->protocol . '://' . $this->domain . '/api/thumbnails' . $this->contentType;
        $request = $this->Request($url, $post, 'PUT');
        $resp = json_decode($request);

        return $resp;
    }

    // Список файлов
    public function getFiles($post = []) {
        $url = $this->protocol . '://' . $this->domain . '/api/files' . $this->contentType;
        $request = $this->Request($url, $post);
        $resp = json_decode($request);

        return $resp;
    }

    // Создание видео
    public function upload($post = [])
    {
        $url = $this->protocol . '://' . $this->domain . '/api/events' . $this->contentType;
        $request = $this->Request($url, $post, 'POST');
        $resp = json_decode($request);

        return $resp;
    }

    // Список каналов
    public function getChannels($post = [])
    {
        $url = $this->protocol . '://' . $this->domain . '/api/channels' . $this->contentType;
        $request = $this->Request($url, $post, 'GET');
        $resp = json_decode($request);

        return $resp;
    }

    // Стрим
    public function stream($post = [])
    {
        $url = $this->protocol . '://' . $this->domain . '/api/events' . $this->contentType;
        $request = $this->Request($url, $post, 'POST');
        $resp = json_decode($request);

        return $resp;
    }

    // Список видео
    public function getEventData($post) {
        $url = $this->protocol . '://' . $this->domain . '/api/events' . $this->contentType;
        $request = $this->Request($url, $post);
        $resp = json_decode($request);

        return $resp;
    }

    private function Request($url, $post = FALSE, $method = 'GET')
    {
        $post['cid'] = $this->cid;

        if(is_array($post))
            ksort($post);

        $str = '';

        if(is_array($post)){
            foreach($post as $key => $val){
                $str .= $key . '=' . $val . '&';
            }
        }
        
        $str = mb_substr($str, 0, -1);

        $hash = md5($str . $this->secret);

        $post['sig'] = $hash;

        $query = http_build_query((array) $post);

        if($method != 'POST'){
            $url = $url . '?' . $query;
        }

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_POST, $method == 'POST');

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
//
//        curl_setopt($ch, CURLOPT_PUT, $method == 'PUT');

        if($method == 'POST' || $method == 'PUT'){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }

        $body = curl_exec($ch);
        curl_close($ch);

        return $body;
    }
}