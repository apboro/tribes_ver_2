<?php

namespace App\Domain\Scripts;

use Illuminate\Support\Facades\Log;
use stdClass;

class TelegramResponseErrorLogger
{
    /**
     * @throws \JsonException
     */
    public static function check(stdClass $response, $domainDirection)
    {
        if($response->ok === false) {
            $response = json_encode($response, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
            Log::error($domainDirection, ['response' => $response]);
        }
        Log::info('end check response');
    }
}