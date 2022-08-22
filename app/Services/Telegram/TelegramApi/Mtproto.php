<?php 

namespace App\Services\Telegram\TelegramApi;

use Illuminate\Support\Facades\Http;

class Mtproto
{
    /**
     * Авторизация пользователя
     *
     * @param int $user_id        id пользователя
     * @param string $phone       номер телефона пользователя в формате '+79191234567'
     * @param int $code           код подтверждения. Приходит в телеграм пользователя после первой отправки запроса авторизации.
     * @return object|array
     */
    public function auth($user_id, $phone, $code = null)
    {
        $params = [
            'ident' => env('APP_NAME') . $user_id,
            'phone' => $phone,
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
    public function logOut($user_id)
    {
        $params = [
            'ident' => env('APP_NAME') . $user_id,
        ];
        return $this->request('/logout', $params)->object();
    }

    /**
     * Получить историю сообщений
     *
     * @param int $user_id 
     * @param string $phone
     * @param string $type               Тип чата - 'channel' 'group'.    Супергруппа и гигагруппа относится к channel
     * @param int $chat_id       
     * @param string|null $access_hash   Хеш доступа обязательно в строке. Хеш доступа есть только у типа 'channel'. Получить можно через getDialogs()
     * @param int|null $min_id           Если было передано положительное значение, метод вернет только сообщения с идентификаторами больше min_id. 
     * @param int|null $limit            Сколько вернуть результатов. 
     * @return object|array
     */
    public function getMessages($user_id, $phone, $type, $chat_id, $access_hash = null, $min_id = null, $limit = null)
    {
        $params = [
            'ident' => env('APP_NAME') . $user_id,
            'phone' => $phone,
            'type' => $type,
            'chat_id' => $chat_id,
            'access_hash' => $access_hash,
            'min_id' => $min_id,
            'limit' => $limit
        ];
        return $this->request('/history', $params)->object();
    }

    
    public function getReactions($user_id, $phone, $type, $chat_id, $message_id, $access_hash = null, $limit = null)
    {
        $params = [
            'ident' => env('APP_NAME') . $user_id,
            'phone' => $phone,
            'type' => $type,
            'chat_id' => $chat_id,
            'id' => $message_id,
            'access_hash' => $access_hash,
            'limit' => $limit
        ];
        return $this->request('/reactions', $params)->object();
    }

    /**
     * Возвращает информация о ГРУППЕ в том числе и всех её участников
     *
     * @param int $user_id
     * @param string $phone
     * @param int $chat_id
     * @return object|array
     */
    public function getChatInfo($user_id, $phone, $chat_id)
    {
        $params = [
            'ident' => env('APP_NAME') . $user_id,
            'phone' => $phone,
            'chat_id' => $chat_id
        ];
        return $this->request('/chat-info', $params)->object();
    }

    /**
     * Получить диалоги пользователя и информацию о них
     *
     * @param int $user_id
     * @param string $phone
     * @param int|null $limit
     * @return object|array
     */
    public function getDialogs($user_id, $phone, $limit = null)
    {
        $params = [
            'ident' => env('APP_NAME') . $user_id,
            'phone' => $phone,
            'limit' => $limit
        ];
        return $this->request('/dialogs', $params)->object();
    }

    /**
     * Получить информацию о пользователях канала
     *
     * @param int $user_id
     * @param string $phone
     * @param int $channel_id
     * @param string $access_hash
     * @param int|null $limit
     * @return object|array
     */
    public function getUsersInChannel($user_id, $phone, $channel_id, $access_hash, $limit = null)
    {
        $params = [
            'ident' => env('APP_NAME') . $user_id,
            'phone' => $phone,
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