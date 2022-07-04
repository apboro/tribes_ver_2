<?php

namespace App\Services\Telegram\Extention;

use App\Services\Telegram\BotInterface\ExtentionApiInterface;
use Askoldex\Teletant\Api;
use Askoldex\Teletant\Settings;
use Askoldex\Teletant\Log;
use Illuminate\Support\Facades\Http;

class ExtentionApi extends Api implements ExtentionApiInterface
{
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
     *
     */
    public function sendMess(int $chatId, string $text, bool $preview = false, array $keyboard = [])
    {
        $params = [
            'chat_id'        => $chatId,
            'text'           => $text,
            'parse_mode'     => 'HTML',
            'disable_web_page_preview' => $preview,
            'reply_markup'   => [
                "inline_keyboard" => $keyboard
            ]
        ];
        Http::post(env('TELEGRAM_BASE_URL') . '/bot' . $this->token . '/sendMessage', $params);
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
        return $this->invokeAction('banChatMember', [
            'chat_id'        => $chatId,
            'user_id'        => $userId
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
            'chat_id'        => $chatId,
            'user_id'        => $userId,
            'only_if_banned' => true
        ];

        return $this->unbanChatMember($params);
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
}