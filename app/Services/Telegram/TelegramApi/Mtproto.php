<?php 

namespace App\Services\Telegram\TelegramApi;

use Illuminate\Support\Facades\Http;

class Mtproto
{

    /**
     * Установить вебхук для получения обновлений
     *
     * @param string $url
     * @param integer $user_id
     * @return void
     */
    public function setWebhook($url, $user_id = 1)
    {
        $params = [
            'ident' => env('APP_NAME') . $user_id,
            'url' => $url
        ];
        return $this->request('/start-listen', $params)->object();
    }

    /**
     * Авторизация пользователя
     *
     * @param int $code           код подтверждения. Приходит в телеграм пользователя после первой отправки запроса авторизации.
     * @param int $user_id        id пользователя
     * @return object|array
     */
    public function auth($user_id = 1, $code = null)
    {
        $params = [
            'ident' => env('APP_NAME') . $user_id,
            'code' => $code
        ];
        return $this->request('/auth', $params)->object();
    }

    /**
     * Завершить сеанс пользователя
     *
     * @param int $user_id
     * @return object|array
     */
    public function logOut($user_id = 1)
    {
        $params = [
            'ident' => env('APP_NAME') . $user_id,
        ];
        return $this->request('/logout', $params)->object();
    }

    /**
     * Получить историю сообщений
     *
     * @param string $type               Тип чата - 'channel' 'group'.    Супергруппа и гигагруппа относится к channel
     * @param int $chat_id       
     * @param string|null $access_hash   Хеш доступа обязательно в строке. Хеш доступа есть только у типа 'channel'. Получить можно через getDialogs()
     * @param int|null $min_id           Если было передано положительное значение, метод вернет только сообщения с идентификаторами больше min_id. 
     * @param int|null $limit            Сколько вернуть результатов. 
     * @param int $user_id        id пользователя
     * @return object|array
     */
    public function getMessages($type, $chat_id, $access_hash = null, $min_id = null, $limit = null, $user_id = 1)
    {
        $params = [
            'ident' => env('APP_NAME') . $user_id,
            'type' => $type,
            'chat_id' => $chat_id,
            'access_hash' => $access_hash,
            'min_id' => $min_id,
            'limit' => $limit
        ];
        return $this->request('/history', $params)->object();
    }

    /**
     * Получить просмотры сообщения
     *
     * @param int $chat_id
     * @param array $message_id
     * @param string $access_hash
     * @param string $type
     * @param integer $user_id
     * @return void
     */
    public function getMessagesViews($chat_id, $message_id, $access_hash = null, $type = 'channel', $user_id = 1)
    {
        $params = [
            'ident' => env('APP_NAME') . $user_id,
            'message_id' => $message_id,
            'chat_id' => $chat_id,
            'access_hash' => $access_hash,
            'type' => $type
        ];
        return $this->request('/views', $params)->object();
    }

    /**
     * Получить реакции сообщения в канале или группе
     *
     * @param int $chat_id
     * @param array $message_id
     * @param string $access_hash
     * @param string $type
     * @param int $user_id        id пользователя
     * @return object|array
     */
    public function getChannelReactions($chat_id, $message_id, $access_hash = null, $type = 'channel', $user_id = 1) 
    {
        $params = [
            'ident' => env('APP_NAME') . $user_id,
            'message_id' => $message_id,
            'chat_id' => $chat_id,
            'access_hash' => $access_hash,
            'type' => $type
        ];
        return $this->request('/channel-reactions', $params)->object();
    }

    /**
     * Получить реакции на сообщение в группе с пользователями их оставившие
     *
     * @param int $chat_id
     * @param array $message_id
     * @param int|null $limit
     * @param int $user_id        id пользователя
     * @return object|array
     */
    public function getReactions($chat_id, $message_id, $limit = null, $user_id = 1)
    {
        $params = [
            'ident' => env('APP_NAME') . $user_id,
            'message_id' => $message_id,
            'chat_id' => $chat_id,
            'limit' => $limit
        ];
        return $this->request('/reactions', $params)->object();
    }

    /**
     * Возвращает информация о ГРУППЕ в том числе и всех её участников
     *
     * @param int $chat_id
     * @param int $user_id        id пользователя
     * @return object|array
     */
    public function getChatInfo($chat_id, $user_id = 1)
    {
        $params = [
            'ident' => env('APP_NAME') . $user_id,
            'chat_id' => $chat_id
        ];
        return $this->request('/chat-info', $params)->object();
    }

    /**
     * Получить диалоги пользователя и информацию о них
     *
     * @param int|null $limit
     * @param int $user_id        id пользователя
     * @return object|array
     */
    public function getDialogs($limit = null, $user_id = 1)
    {
        $params = [
            'ident' => env('APP_NAME') . $user_id,
            'limit' => $limit
        ];
        return $this->request('/dialogs', $params)->object();
    }

    /**
     * Получить информацию о пользователях канала
     *
     * @param int $channel_id
     * @param string $access_hash
     * @param int|null $limit
     * @param int $user_id        id пользователя
     * @return object|array
     */
    public function getUsersInChannel($channel_id, $access_hash, $limit = null, $user_id = 1)
    {
        $params = [
            'ident' => env('APP_NAME') . $user_id,
            'channel_id' => $channel_id,
            'access_hash' => $access_hash,
            'limit' => $limit
        ];
        return $this->request('/participants', $params)->object();
    }

    protected function request($address, $params) 
    {
        return Http::get(env('MTPROTO_HOST') . $address, $params);
    }
}