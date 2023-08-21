<?php

namespace App\Services\Telegram\Extention;

use App\Models\Community;
use App\Models\TelegramUserCommunity;
use App\Models\TelegramUserList;
use App\Services\Telegram\BotInterface\ExtentionApiInterface;
use Askoldex\Teletant\Api;
use Askoldex\Teletant\Exception\TeletantException;
use Askoldex\Teletant\Settings;
use Askoldex\Teletant\Log;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log as Logg;
use stdClass;

class ExtentionApi extends Api implements ExtentionApiInterface
{
    private const TELEGRAM_BASE_URL = 'https://api.telegram.org';

    private string $token;

    public function __construct(Settings $settings, Log $logger, string $token)
    {
        parent::__construct($settings, $logger);
        $this->token = $token;
    }

    /**
     * Отправляет сообщение
     * @param int $chatId
     * @param string $text
     * @param bool $preview
     * @param array $keyboard
     * @param bool $silent
     *
     */
    public function sendMess(int $chatId, string $text, bool $preview = false, array $keyboard = [], bool $silent = false)
    {
        try {
            $params = [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => $preview,
                'disable_notification' => $silent,
                'reply_markup' => [
                    "inline_keyboard" => $keyboard
                ]
            ];

            Logg::debug('Lets send mess', [$params]);
            $url = self::TELEGRAM_BASE_URL . '/bot' . $this->token . '/sendMessage';

            Http::post($url, $params);

        } catch (\Exception $e) {
            Logg::channel('telegram-bot-log')
                ->alert('Error from ' . get_called_class() . ' text: ' . $e->getMessage() . PHP_EOL);
        }
    }

    public function sendMessWithReturn(int $chatId, string $text, bool $preview = false, array $keyboard = [], bool $silent = false)
    {
        try {
            $params = [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => $preview,
                'disable_notification' => $silent,
                'reply_markup' => [
                    "inline_keyboard" => $keyboard
                ]
            ];

            Logg::debug('Lets send mess with return params', [$params]);
            $url = self::TELEGRAM_BASE_URL . '/bot' . $this->token . '/sendMessage';

            $response = Http::post($url, $params);

            return $response->json();

        } catch (\Exception $e) {
            Logg::channel('telegram-bot-log')
                ->alert('Error from ' . get_called_class() . ' text: ' . $e->getMessage() . PHP_EOL);
        }
    }

    /**
     * Send image
     *
     * @param string $chatId
     * @param string $photoUrl
     * @param string $caption
     *
     * @return \Illuminate\Http\Client\Response|void
     */
    public function sendImage(string $chatId, string $photoUrl, string $caption): stdClass
    {
        try {
            $paramsToImage = [
                'chat_id' => $chatId,
                'photo' => $photoUrl,
                'caption' => $caption
            ];

            $response = Http::post(self::TELEGRAM_BASE_URL . '/bot' . $this->token . '/sendPhoto', $paramsToImage);

            return json_decode($response->body());

        } catch (\Exception $e) {
            Logg::channel('telegram-bot-log')
                ->alert('Error from ' . get_called_class() . ' text: ' . $e->getMessage() . PHP_EOL);
        }
    }

    /**
     * Pin message
     *
     * @param string $chatId
     * @param string $messageId
     *
     * @return Response|void
     */
    public function pinMessage(string $chatId, string $messageId)
    {
        try {
            $pinMessage = [
                'chat_id' => $chatId,
                'message_id' => $messageId,
                //            'disable_notification' => true, // default  muted false
            ];

            $response = Http::post(self::TELEGRAM_BASE_URL . '/bot' . $this->token . '/pinChatMessage', $pinMessage);

            return json_decode($response->body());
        } catch (\Exception $e) {
            Logg::channel('telegram-bot-log')
                ->alert('Error from ' . get_called_class() . ' text: ' . $e->getMessage() . PHP_EOL);
        }
    }

    /**
     * Пригласительная ссылка с лимитом
     * @param int $chatId
     * @param int $member_limit
     *
     */
    public function createAdditionalLink(int $chatId, int $member_limit = 1)
    {
        $params = [
            'chat_id' => $chatId,
            'member_limit' => $member_limit
        ];
        $query = Http::post(env('TELEGRAM_BASE_URL') . '/bot' . $this->token . '/createChatInviteLink', $params);
        return $query;
    }

    /**
     * метод для пересылки сообщений любого типа
     *
     * @param integer $chatId
     * @param integer $fromChatId
     * @param integer $messageId
     * @param boolean $disableNotification
     * @param boolean $protectContent
     * @return object
     */
    public function forwardMess(int $chatId, int $fromChatId, int $messageId, bool $disableNotification = false, bool $protectContent = false)
    {
        $params = [
            'chat_id' => $chatId,
            'from_chat_id' => $fromChatId,
            'disable_notification' => $disableNotification,
            'protect_content' => $protectContent,
            'message_id' => $messageId
        ];
        return $this->forwardMessage($params);
    }

    /**
     * Банит пользователя
     *
     * @param int $userId
     * @param int $chatId
     * @return object
     */
    public function kickUser(int $userId, int $chatId)
    {
        try {
            $community = Community::getCommunityByChatId($chatId);
            TelegramUserList::firstOrCreate([
                "community_id" => $community->id,
                "telegram_id" => $userId,
                "type" => 4
            ]);
            $telegramUserCommunity = TelegramUserCommunity::getByCommunityIdAndTelegramUserId($community->id, $userId);
            if ($telegramUserCommunity){
                $telegramUserCommunity->exit_date = time();
                $telegramUserCommunity->status = 'banned';
                $telegramUserCommunity->save();
            }
            return $this->invokeAction('banChatMember', [
                'chat_id' => $chatId,
                'user_id' => $userId
            ]);

        } catch (\Exception $e) {
            Logg::error($e);
        }
    }

    /**
     * Mute user
     *
     * @param int $userId
     * @param int $chatId
     * @return object
     */
    public function muteUser(int $userId, int $chatId, int $time)
    {
        return $this->invokeAction('restrictChatMember', [
            'chat_id' => $chatId,
            'user_id' => $userId,
            'permissions' => json_encode(array('can_send_messages' => false, 'can_invite_users' => false)),
            'until_date' => time() + $time
        ]);
    }

    /**
     * Снимает бан с пользователя
     *
     * @param int $userId
     * @param int $chatId
     * @return object
     */
    public function unKickUser(int $userId, int $chatId)
    {
        $params = [
            'chat_id' => $chatId,
            'user_id' => $userId,
            'only_if_banned' => true
        ];
        $community = Community::getCommunityByChatId($chatId);
        $telegramUserInList = TelegramUserList::where([
            "community_id" => $community->id,
            "telegram_id" => $userId,
            "type" => 4
        ])->first();

        if ($telegramUserInList) $telegramUserInList->delete();

        $telegramUserCommunity = TelegramUserCommunity::getByCommunityIdAndTelegramUserId($community->id, $userId);
        if ($telegramUserCommunity) {
            $telegramUserCommunity->status = null;
            $telegramUserCommunity->save();
        }
        if ($community->connection->chat_type === 'supergroup') {
            return $this->unbanChatMember($params);
        }
    }

    /**
     * Создаёт пригласительную ссылку
     *
     * @param integer $chatId
     * @return string
     */
    public function createInviteLink(int $chatId)
    {
        $params = [
            'chat_id' => $chatId
        ];

        return $this->exportChatInviteLink($params)->getResult();
    }

    /**
     * Количесвто участников в чате
     *
     * @param integer $chatId
     * @return int
     */
    public function getChatCount(int $chatId)
    {
        return $this->invokeAction('getChatMemberCount', [
            'chat_id' => $chatId
        ])->getResult();
    }

    /**
     * Список администраторов в чате
     *
     * @param integer $chatId
     * @return array
     */
    public function getChatAdministratorsList(int $chatId)
    {
        return $this->invokeAction('getChatAdministrators', [
            'chat_id' => $chatId
        ])->getResult();
    }

    /**
     * получить список изображений профиля для пользователя
     *
     * @param integer $userId
     * @param integer $offset
     * @param integer $limit
     * @return object
     */
    public function getUserPhotos(int $userId, int $offset = 1, int $limit = 1)
    {
        $params = [
            'user_id' => $userId,
            'offset' => $offset,
            'limit' => $limit
        ];
        return $this->getUserProfilePhotos($params);
    }

    public function deleteUserMessage(int $messageId, int $chatId)
    {
        $params = [
            'chat_id' => $chatId,
            'message_id' => $messageId,
        ];
        return $this->invokeAction('deleteMessage', $params);

    }
}