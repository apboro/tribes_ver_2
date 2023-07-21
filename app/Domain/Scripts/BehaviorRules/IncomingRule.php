<?php
namespace App\Domain\Scripts\BehaviorRules;

use App\Domain\Scripts\TelegramResponseErrorLogger;
use App\Models\Community;
use App\Models\CommunityRule;
use App\Models\TelegramConnection;
use App\Services\Telegram\Extention\ExtentionApi;
use Illuminate\Support\Facades\Log;
use stdClass;

final class IncomingRule
{
    private ExtentionApi $telegramApi;
    private int $directionId;

    public function __construct(ExtentionApi $telegramApi, int $directionId)
    {
        $this->telegramApi = $telegramApi;
        $this->directionId = $directionId;
    }

    public function __invoke(CommunityRule $communityRule)
    {
        $userId = $communityRule->user_id;
        $community = Community::select('connection_id')->where('id', $this->directionId)->where('owner', $userId)->first();

        /** @var TelegramConnection $telegramConnections */
        $telegramConnections = TelegramConnection::where('id', '=', $community->connection_id)->first();
 
        $chatId = $telegramConnections->chat_id;
        $content = $communityRule->content;

        if ($communityRule->content_image_path !== NULL){
            $imageUrl = config('app.url') . '/'. $communityRule->content_image_path;
            log::info('image url:'. $imageUrl);

            /** @var stdClass $response */
            $response = $this->telegramApi->sendImage($chatId, $imageUrl, $content);
            TelegramResponseErrorLogger::check($response, 'IncomingRule send image');
            $messageId = $response->result->message_id;
        } else {
            $response = $this->telegramApi->sendMessWithReturn($chatId, $content);
            $messageId = $response['result']['message_id'];
        }

        /** @var stdClass $responsePin */
        $responsePin = $this->telegramApi->pinMessage($chatId, $messageId);

        TelegramResponseErrorLogger::check($responsePin, 'IncomingRule pin message');
    }
}