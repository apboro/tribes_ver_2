<?php

namespace App\Services\Yookassa;

use App\Models\YookassaKey;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class OAuth
{

    public static function getOAuthLink(int $shopId): string
    {
        $applicationId = config('yookassa.oauth_application_id');

        return 'https://yookassa.ru/oauth/v2/authorize?client_id=' . $applicationId . 
                '&response_type=code&state=' . $shopId;
    }

    public static function exchangeKeyToOAuth(string $code, int $shopId): string
    {
        $result = self::receiveOauthKey($code); 
           
        if (isset($result['error'])) {
            return $result['error'];
        }
        if (!isset($result['access_token']) || !isset($result['access_token'])) {
            return 'Невозможно получить OAuth-токен';
        }
        $expiresTimeStamp = time() + $result['expires_in'];
        $expiresAt = Carbon::createFromTimestamp($expiresTimeStamp);
        YookassaKey::updateOrCreate(['shop_id' => $shopId],
                                ['oauth' => $result['access_token'],
                                'end_at' => $expiresAt]);

        return 'OAuth-токен успешно сохранен';
    }

    private static function receiveOauthKey(string $code)
    {
        $applicationId = config('yookassa.oauth_application_id');
        $applicationSecret = config('yookassa.oauth_secret');

        $client = new Client();
        try {
            $response = $client->post('https://yookassa.ru/oauth/v2/token', [
                'auth' => [$applicationId, $applicationSecret],
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'code' => $code
                ]
            ]);            
        } catch (\Exception $e) {
            Log::debug('Yookassa в ответе передала ошибку');
            return ['error' => 'Ошибка получения ответа от Yookassa'];
        }

        $statusCode = $response->getStatusCode();
        if ($statusCode != 200) {
            Log::debug('Yookassa не ответила кодом 200', ['response' => $response]);
            return ['error' => 'Ошибка получения ответа от Yookassa'];
        }

        $body = $response->getBody();
        Log::debug('Ответ Yookassa при получении oauth key', ['response' => $response, 'body' => $body]);

        return json_decode($body, true); 
    } 
}