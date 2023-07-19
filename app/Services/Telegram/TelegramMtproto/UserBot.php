<?php 

namespace App\Services\Telegram\TelegramMtproto;

use Illuminate\Support\Facades\Http;
use Log;

class UserBot
{

    /**
     * Установить вебхук для получения обновлений
     *
     * @param string $url // Куда отправлять события
     * @param integer $user_id
     * @return void
     */
    public function setWebhook($url, $user_id = 1)
    {

        Log::info('setWebhook  USER BOT');

        $params = [
            'ident' => env('IDENT_USER_BOT') . $user_id,
            'phone' => env('PHONE_USER_BOT'),
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
    public function auth($code = null, $user_id = 1)
    {
        $params = [
            'ident' => env('IDENT_USER_BOT') . $user_id,
            'phone' => env('PHONE_USER_BOT'),
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
            'ident' => env('IDENT_USER_BOT') . $user_id,
        ];
        return $this->request('/logout', $params)->object();
    }

    /**
     * Получить историю сообщений
     *
     * @param int $chat_id 
     * @param string $type               Тип чата - 'channel' 'group'.    Супергруппа и гигагруппа относится к channel      
     * @param string|null $access_hash   Хеш доступа обязательно в строке. Хеш доступа есть только у типа 'channel'. Получить можно через getDialogs()
     * @param int|null $min_id           Если было передано положительное значение, метод вернет только сообщения с идентификаторами больше min_id. 
     * @param int|null $limit            Сколько вернуть результатов. 
     * @param int|null $offset_id
     * @param int $user_id        id пользователя
     * @return object|array
     */
    public function getMessages($chat_id, $type, $access_hash = null, $min_id = null, $limit = null, $offset_id = null, $user_id = 1)
    {
        log::info('getMessages');
        $params = [
//            'ident' => env('IDENT_USER_BOT') . $user_id,
//            'phone' => env('PHONE_USER_BOT'),
            'chatId' => $chat_id,
            'type' => $type,
//            'access_hash' => $access_hash,
            'min_id' => $min_id,
            'limit' => $limit,
            'offset_id' => $offset_id
        ];
        $uri = '?chatId='. $chat_id;

        return $this->requestSimple( 'group-messages', $uri )->object();
    }

    /**
     * Получить просмотры сообщения
     *
     * @param int $chat_id
     * @param array $messages_id
     * @param string $access_hash
     * @param string $type
     * @param integer $user_id
     * @return void
     */
    public function getMessagesViews($chat_id, $type, $messages_id, $access_hash = null, $user_id = 1)
    {
        $params = [
            'ident' => env('IDENT_USER_BOT') . $user_id,
            'phone' => env('PHONE_USER_BOT'),
            'message_id' => $messages_id,
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
     * @param array $messages_id
     * @param string $access_hash
     * @param string $type
     * @param int $user_id        id пользователя
     * @return object|array
     */
    public function getChannelReactions($chat_id, $messages_id, $access_hash = null, $type = 'channel', $user_id = 1) 
    {
        $params = [
            'ident' => env('IDENT_USER_BOT') . $user_id,
            'phone' => env('PHONE_USER_BOT'),
            'message_id' => $messages_id,
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
     * @param int $message_id
     * @param int|null $limit
     * @param int|null $offset
     * @param int $user_id        id пользователя
     * @return object|array
     */
    public function getReactions($chat_id, $message_id, $limit = null, $offset = null, $user_id = 1)
    {
        $params = [
            'ident' => env('IDENT_USER_BOT') . $user_id,
            'phone' => env('PHONE_USER_BOT'),
            'message_id' => $message_id,
            'chat_id' => $chat_id,
            'limit' => $limit,
            'offset' => $offset
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
            'ident' => env('IDENT_USER_BOT') . $user_id,
            'phone' => env('PHONE_USER_BOT'),
            'chat_id' => $chat_id
        ];

        return $this->request('/chat-info', $params)->object();
    }

    /**
     * Получить диалоги пользователя и информацию о них
     *
     * @param int|null $limit
     * @param int $user_id        id пользователя
     * @param int|null $offset_id
     * @return object|array
     */
    public function getDialogs($limit = null, $offset_id = null, $user_id = 1)
    {
//        $params = [
//            'ident' => env('IDENT_USER_BOT') . $user_id,
//            'phone' => env('PHONE_USER_BOT'),
//            'limit' => $limit,
//            'offset_id' => $offset_id
//        ];
//        return $this->request('/dialogs', $params)->object();
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
    public function getUsersInChannel($channel_id, $access_hash = null, $limit = null, $offset = null, $user_id = 1)
    {
        $params = [
            'chatId' => '',
//            'ident' => env('IDENT_USER_BOT') . $user_id,
//            'phone' => env('PHONE_USER_BOT'),
            'channel_id' => $channel_id,
            'access_hash' => $access_hash,
            'limit' => $limit,
            'offset' => $offset
        ];
        return $this->requestSimpleGet('group-members?chatId=' . $channel_id )->object();
//        $res = json_encode($res, JSON_UNESCAPED_UNICODE);
//
//        file_put_contents(storage_path('users.log'),  $res);
//        $res =  json_decode(file_get_contents(storage_path('users.log')), true);
//        return $res;
        //gateway/api/telegram-client/group-members?chatId=-908763618

//        return $this->requestSimpleGet('/group-members?chatId=' . $channel_id )->object();
    }

    protected function request($address, $params) 
    {
        return Http::get(env('MTPROTO_HOST') . $address, $params);
    }

    protected function requestSimpleGet($address)
    {
        $url = env('MTPROTO_HOST') . $address;
        log::info('url: ' . $url);

        return Http::get(env('MTPROTO_HOST') . $address);
    }

    protected function requestSimple($uri = 'group-messages' , $params)
    {
        $url = env('MTPROTO_HOST') . $uri . $params;

        return Http::get($url );
    }

    protected function requestPost($params)
    {
        return Http::post(env('MTPROTO_HOST') , $params);
    }
}