<?php

namespace App\Services\Yookassa;

use App\Models\YookassaKey;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use YooKassa\Client as YooKassaClient;
use YooKassa\Model\NotificationEventType;

class OAuth
{

    public static function getOAuthLink(int $shopId): string
    {
        $applicationId = config('yookassa.oauth_application_id');

        return 'https://yookassa.ru/oauth/v2/authorize?client_id=' . $applicationId . 
                '&response_type=code&state=' . $shopId;
    }

    public static function exchangeKeyToOAuth(string $code, int $shopId): array
    {
        $result = self::receiveOauthKey($code); 
           
        if (isset($result['error'])) {
            return ['status' => 'error', 
                    'message' => $result['error']];
        }
        if (!isset($result['access_token'])) {
            return ['status' => 'error', 
                    'message' => 'Невозможно получить OAuth-токен'];
        }
        $expiresTimeStamp = time() + $result['expires_in'];
        $expiresAt = Carbon::createFromTimestamp($expiresTimeStamp);
        $bearerToken = $result['access_token'];
        YookassaKey::updateOrCreate(['shop_id' => $shopId],
                                ['oauth' => $bearerToken,
                                'end_at' => $expiresAt]);

        self::subscribeWebhook($bearerToken);

        return ['status' => 'success', 
                'message' => 'OAuth-токен успешно сохранен'];
    }

    private static function subscribeWebhook(string $bearerToken)
    {
        $client = new YooKassaClient();
        $client->setAuthToken($bearerToken);
        
        $client->addWebhook([
            "event" => NotificationEventType::PAYMENT_SUCCEEDED,
            "url"   => route('yookassa.notify')
        ]);
    }

    private static function receiveOauthKey(string $code)
    {
        $applicationId = config('yookassa.oauth_application_id');
        $applicationSecret = config('yookassa.oauth_secret');

        try {
           $response = Http::asForm()->withHeaders([
                'Authorization' => 'Basic ' . base64_encode($applicationId . ':' . $applicationSecret),
                'Content-Type' => 'application/x-www-form-urlencoded',
                ])->post('https://yookassa.ru/oauth/v2/token', [
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                ]);      
        } catch (\Exception $e) {
            Log::debug('Yookassa в ответе передала ошибку');
            return ['error' => 'Ошибка получения ответа от Yookassa'];
        }

        $statusCode = $response->status();
        if ($statusCode != 200) {
            Log::debug('Yookassa не ответила кодом 200', ['response' => $response]);
            return ['error' => 'Ошибка получения ответа от Yookassa'];
        }

        $body = $response->body();
        Log::debug('Ответ Yookassa при получении oauth key', ['response' => $response, 'body' => $body]);

        return json_decode($body, true); 
    } 
}