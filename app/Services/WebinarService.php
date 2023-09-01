<?php

namespace App\Services;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class WebinarService
{
    public const CID = 'spodial';
    private $base_url = "https://wbnr.su:50443/api/";
    private $apikey;
    public string $redirectUrl;

    public function __construct()
    {
        $this->redirectUrl = null;
        $this->apikey = config('webinars.api_key');
    }

    public function setWebinarRole(string $meet, User $user, string $role)
    {
        $params = [
            'meet'     => $meet,
            'outer_id' => $user->id,
            'email'    => $user->email,
            'name'     => $user->name,
            'role'     => $role,
        ];

        $this->setSpecificUrl();

        $result = $this->sendRequest('rooms', $params, 'get');
        if($role !== 'admin') {
            log::info('not admin');
            return $this->redirectUrl;
        }
        log::info('is admin');
        return $result;
    }

    private function setSpecificUrl(): void
    {
        $this->base_url = str_replace('api/',  '',$this->base_url);
    }

    private function sendRequest(string $apiMethod, array $queryParams, $method = 'post')
    {
        $httpQuery = $this->prepareHttpQuery($queryParams);
        $client = new Client();
        $url = $this->base_url . $apiMethod .'?' . $httpQuery;

        $this->redirectUrl = $url;

        log::info('webinar url: ' . json_encode($url, JSON_UNESCAPED_UNICODE));

        switch ($method) {
            case 'get':
                $res = $client->get($url);
                break;
            default:
                $res = $client->post($url);
        }

        if ($res->getStatusCode() !== 200) {
            log::error('webinar '. $apiMethod.' response  status:'.  $res->getStatusCode());
            return false;
        }

        $room_data = json_decode($res->getBody());
        if (empty($room_data) || empty($room_data->room)) {
            log::error('webinar '. $apiMethod .'body status:'.  $res->getStatusCode());
            return false;
        }

        return $room_data->room;
    }

    public function add(array $add_params)
    {
        return $this->sendRequest('webinars', $add_params);
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
        $params['cid'] = self::CID;
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