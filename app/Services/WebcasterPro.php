<?php

namespace App\Services;

class WebcasterPro 
{
    private $domain = 'ugc.webcaster.pro';
    private $client_id = 817;
    private $protocol = 'https';

    public function uploads($file)
    {
        $url = $this->protocol . '://' . $this->domain . '/uploads/' . $this->client_id;
        $request = $this->request($url, $file);
        $resp = json_decode($request);

        return $resp;
    }

    private function request($url, $file)
    {
        $curl_file = curl_file_create($file);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERPWD, config('services.webcasterPro.client_name') . ':' . config('services.webcasterPro.client_password'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('data' => $curl_file));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $response = curl_exec($ch);
        curl_close($ch);	
         
        return $response;
    } 
}