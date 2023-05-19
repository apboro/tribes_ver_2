<?php
namespace App\Domain\Scripts\BehaviorRules;

use App\Domain\Scripts\TelegramResponseErrorLogger;
use App\Models\CommunityRule;
use App\Models\TelegramConnection;
use App\Services\Telegram\Extention\ExtentionApi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use stdClass;

final class IncomingRule
{
    private ExtentionApi $telegramApi;

    public function __construct(ExtentionApi $telegramApi)
    {
        $this->telegramApi = $telegramApi;
    }

    public function __invoke(CommunityRule $communityRule)
    {
        $userId = $communityRule->user_id;
        /** @var TelegramConnection $telegramConnections */
        $telegramConnections = TelegramConnection::where('user_id', '=', $userId)->first();

        $chatId = $telegramConnections->chat_id;
        $content = $communityRule->content;

        $imageUrl = env('APP_URL') . $communityRule->content_image_path;
        log::info('image url:'. $imageUrl);

        /** @var stdClass $response */
        $response = $this->telegramApi->sendImage($chatId, $imageUrl, $content);

        TelegramResponseErrorLogger::check($response, 'IncomingRule send image');

        /** @var stdClass $responsePin */
        $responsePin = $this->telegramApi->pinMessage($chatId,$response->result->message_id);

        TelegramResponseErrorLogger::check($responsePin, 'IncomingRule pin message');
    }
}