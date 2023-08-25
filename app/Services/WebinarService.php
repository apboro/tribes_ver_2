<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class WebinarService
{
    private $base_url = "https://wbnr.su:50443/api/";
    private $apikey;

    public function __construct()
    {
        $this->apikey = config('webinars.api_key');
    }

    public function add(array $add_params)
    {
        $http_query_string = $this->prepareHttpQuery($add_params);

        $client = new Client();
        $url = $this->base_url . 'webinars?' . $http_query_string;

        log::info('webinar url: ' . json_encode($url, JSON_UNESCAPED_UNICODE));

        $res = $client->post($url);
        if ($res->getStatusCode() !== 200) {
            return false;
        }
        $room_data = json_decode($res->getBody());
        if (empty($room_data) || empty($room_data->room)) {
            return false;
        }
        return $room_data->room;
    }

    public function update(array $update_params)
    {

        $http_query_string = $this->prepareHttpQuery($update_params);

        $client = new Client();
        $res = $client->patch($this->base_url . 'webinars?' . $http_query_string);
        if ($res->getStatusCode() !== 200) {
            return false;
        }
        $room_data = json_decode($res->getBody());
        if (empty($room_data) || empty($room_data->room)) {
            return false;
        }
        return $room_data->room;
    }

    public function delete(array $delete_params)
    {
        $http_query_string = $this->prepareHttpQuery($delete_params);
        $client = new Client();
        $res = $client->delete($this->base_url . 'webinars?' . $http_query_string);
        if ($res->getStatusCode() !== 200) {
            return false;
        }
        return true;
    }

    private function prepareHttpQuery(array $params)
    {
        $params['cid'] = 'spodial';
        $sign = $this->prepareSign($params);
        log::info('md5='.$sign );
        $params['sign'] = $sign;
        log::info('params:' , $params);

        return http_build_query($params);
    }

    private function prepareSign(array $params)
    {
        ksort($params);
        $string = "";
        foreach ($params as $key => $value) {
            log::info('key: '. $key . ' val: ' . $value);
            $string .= $key . '=' . $value;
        }
        $string .= $this->apikey;
        log::info($string);
        return md5($string);
    }

}