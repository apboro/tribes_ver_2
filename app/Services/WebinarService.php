<?php

namespace App\Services;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class WebinarService
{
    public const CID = 'Spodial';
    private $base_url = "https://wbnr.su:50443/api/";
    private $apikey;

    public function __construct()
    {
        $this->apikey = config('webinars.api_key');
    }

    public function prepareExternalWebinarUrl(string $webinarUrl, User $user, string $role)
    {
        $meet = $this->parseMeet($webinarUrl);
        log::info('parse meet: ' . $meet);

        $params = [
            'meet'     => $meet,
            'name'     => $user->name,
            'email'    => $user->email,
            'outer_id' => $user->id,
            'role'     => $role,
        ];

        $url = $webinarUrl . '&' .$this->prepareHttpQuery($params);

        if($role !== 'admin') {
            log::info('not admin');
        }
        log::info('is admin');

        return $url;
    }

    /**
     * @param string $webinarUrl
     *
     * @return array|mixed|string|string[]|null
     */
    private function parseMeet(string $webinarUrl)
    {
        $meet = null;
        parse_str(parse_url($webinarUrl)['query'], $meet);

        return $meet['meet'];
    }

    private function sendRequest(string $apiMethod, array $queryParams, $method = 'post')
    {
        $httpQuery = $this->prepareHttpQuery($queryParams);
        $client = new Client();
        $url = $this->base_url . $apiMethod .'?' . $httpQuery;

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