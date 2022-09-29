<?php

namespace App\Services\Telegram\MainComponents;

use App\Exceptions\KnowledgeException;
use App\Exceptions\TelegramException;
use App\Helper\ArrayHelper;
use App\Models\Community;
use App\Repositories\Tariff\TariffRepositoryContract;
use App\Services\Telegram;
use App\Services\Telegram\MainBot;
use Exception;

class MainBotEvents
{
    protected MainBot $bot;
    protected ?object $data;

    public function __construct(MainBot $bot, ?object $data)
    {
        $this->bot = $bot;
        if ($data === null)
            throw new TelegramException('Пустой запрос');
        else $this->data = $data;
    }

    public function initEventsMainBot(array $config = [
        'newChatMember',
        'newChatUser',
        'groupChatCreated',
        'chanelChatCreated',
        'checkMember',
        'newChatPhoto',
        'deleteChat',
        'newChatTitle',
        'deleteUser'
    ])
    {
        foreach ($config as $configItem) {
            if (is_string($configItem)) {
                if (method_exists($this, $configItem)) {
                    $this->{$configItem}();
                }
            } else if (is_array($configItem)) {
                foreach ($configItem as $method => $callback) {
                    if (is_array($callback) && count($callback) == 3) {
                        $params = array_pop($callback);
                        if (!is_array($params)) {
                            $exception = new KnowledgeException('Колбек для события с параметрами. Параметры должны быть массивом', [
                                $method,
                                $callback,
                                $params
                            ]);
                            $exception->report();
                            continue;
                        }
                        $this->{$method}($callback, $params);
                    } else if (method_exists($this, $method)) {
                        $this->{$method}($callback);
                    }
                }
            }
        }
    }

    /** Добавление бота в уже существующую ГРУППУ */
    protected function newChatMember()
    {

        try {
            if (isset($this->data->message->new_chat_member->id)) {
                $chatId = $this->data->message->chat->id;
                $this->bot->logger()->debug('новый пользователь в группе', ArrayHelper::toArray($this->data->message->new_chat_member));
                if ($this->data->message->new_chat_member->id == $this->bot->botId) {
                    $this->bot->logger()->debug('Добавление бота в уже существующую ГРУППУ', ArrayHelper::toArray($this->data->message->chat));
                    Telegram::botEnterGroupEvent(
                        $this->data->message->from->id,
                        $chatId,
                        $this->data->message->chat->type,
                        $this->data->message->chat->title,
                        $this->getPhoto($chatId)
                    );
                }
            }
        } catch (Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Новый пользователь */
    protected function newChatUser()
    {
        try {
            if (isset($this->data->message->new_chat_member->id)) {
                if ($this->data->message->new_chat_member->id !== $this->bot->botId) {
                    $chatId = $this->data->message->chat->id;
                    $community = Community::whereHas('connection', function ($q) use ($chatId) {
                        $q->where('chat_id', $chatId);
                    })->select(['id'])->first();

                    if ($community) {


                        $image = !empty($community->tariff->getWelcomeImage()->url) ? '<a href="' . route('main') . $community->tariff->getWelcomeImage()->url . '">&#160</a>' : '';

                        $member = $this->data->message->new_chat_member;
                        if (!empty($member->username) || !empty($member->first_name)) {

                            $userName = !empty($member->username) ? $member->username : '';
                            $firstName = !empty($member->first_name) ? $member->first_name : '';
                            $lastName = !empty($member->last_name) ? $member->last_name : '';

                            $ty = Telegram::registerTelegramUser($member->id, NULL, $userName, $firstName, $lastName);

                            if (!$ty->communities()->find($community->id)) {
                                $ty->communities()->attach($community, [
                                    'role' => 'member',
                                    'accession_date' => time()
                                ]);
                            }

                            $description = $community->tariff->welcome_description;
                            if ($description && $description != '') {
                                $text = ($userName ?: $firstName)
                                    . ', ' . $description . $image;
                                $this->bot->getExtentionApi()->sendMess($chatId, $text);
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Добавление бота в новую ГРУППУ */
    protected function groupChatCreated()
    {
        try {
            if (isset($this->data->message->group_chat_created)) {
                if ($this->data->message->group_chat_created == true) {
                    $chatId = $this->data->message->chat->id;
                    Telegram::botEnterGroupEvent(
                        $this->data->message->from->id,
                        $chatId,
                        $this->data->message->chat->type,
                        $this->data->message->chat->title,
                        $this->getPhoto($chatId)
                    );
                }
            }
        } catch (Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Добавление бота в новый канал */
    protected function chanelChatCreated()
    {
        try {
            if (isset($this->data->my_chat_member)) {
                if (
                    $this->data->my_chat_member->chat->type == 'channel' and
                    $this->data->my_chat_member->new_chat_member->status !== 'left'
                ) {
                    $this->bot->logger()->debug('Добавление бота в новый канал', ArrayHelper::toArray($this->data));
                    $chatId = $this->data->my_chat_member->chat->id;
                    Telegram::botEnterGroupEvent(
                        $this->data->my_chat_member->from->id,
                        $chatId,
                        $this->data->my_chat_member->chat->type,
                        $this->data->my_chat_member->chat->title,
                        $this->getPhoto($chatId)
                    );
                }
            }
        } catch (Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Событие назначения бота администратором в ГРУППЕ */
    protected function checkMember()
    {
        try {
            if (isset($this->data->my_chat_member)) {
                if (
                    $this->data->my_chat_member->new_chat_member->user->id == $this->bot->botId and
                    $this->data->my_chat_member->new_chat_member->status == 'administrator'
                ) {
                    $this->bot->logger()->debug('Бот в группе стал администратором', ArrayHelper::toArray($this->data));
                    $chatId = $this->data->my_chat_member->chat->id;
                    Telegram::botGetPermissionsEvent(
                        $this->data->my_chat_member->from->id,
                        $this->data->my_chat_member->new_chat_member->status,
                        $chatId
                    );
                }
            }
        } catch (Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Событие изменения или добавления фотографии группы или канала*/
    protected function newChatPhoto()
    {
        try {
            if (isset($this->data->message->new_chat_photo)) {
                $chat = $this->data->message;
            } elseif (isset($this->data->channel_post->new_chat_photo)) {
                $chat = $this->data->channel_post;
            }
            if (isset($chat)) {
                $chatId = $chat->chat->id;
                $idPhoto = $chat->new_chat_photo[2]->file_id;
                $urnPhoto = $this->bot->Api()->getFile([
                    'file_id' => $idPhoto
                ])->filePath() ?? NULL;
                if (!$urnPhoto)
                    return false;

                $uriPhoto = env('TELEGRAM_BASE_URL') . '/file/bot' . $this->bot->getToken() . '/' . $urnPhoto;
                Telegram::updateConnectionPhoto(
                    $chatId,
                    $uriPhoto
                );
            }
        } catch (Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Событие удаления бота из чата или удаление чата */
    protected function deleteChat()
    {
        try {
            if (isset($this->data->my_chat_member)) {
                if (
                    $this->data->my_chat_member->new_chat_member->user->id == $this->bot->botId and
                    $this->data->my_chat_member->new_chat_member->status == 'left'
                ) {
                    Telegram::deleteCommunity($this->data->my_chat_member->chat->id);
                }
            }
        } catch (Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Событие удаления пользователя из группы */
    protected function deleteUser()
    {
        try {
            if (isset($this->data->message->left_chat_member)) {
                $telegram = new Telegram(app(TariffRepositoryContract::class));
                $telegram->deleteUser($this->data->message->chat->id, $this->data->message->left_chat_member->id);
            }
        } catch (Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Событие изменения названия группы или канала */
    protected function newChatTitle()
    {
        try {
            if (isset($this->data->message->new_chat_title)) {
                $community = $this->data->message;
            } elseif (isset($this->data->channel_post->new_chat_title)) {
                $community = $this->data->channel_post;
            } else {
                $community = NULL;
            }
            if ($community) {
                Telegram::newTitle(
                    $community->chat->id,
                    $community->new_chat_title
                );
            }
        } catch (Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Возвращает путь для СКАЧИВАНИЯ аватарки чата
     * @param $chatId
     * @return string
     * @throws \Askoldex\Teletant\Exception\TeletantException
     */
    protected function getPhoto($chatId)
    {
        try {
            $photoId = $this->bot->getExtentionApi()->getChat(['chat_id' => $chatId])->photo()->bigFileId() ?? NULL;
            if (!$photoId)
                return '/images/no-image.svg';

            $photoPath = $this->bot->Api()->getFile([
                'file_id' => $photoId
            ])->filePath() ?? NULL;
            if (!$photoPath)
                return '/images/no-image.svg';

            return env('TELEGRAM_BASE_URL') . '/file/bot' . $this->bot->getToken() . '/' . $photoPath;
        } catch (Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
        return '';
    }

    /* Слушатель сообщений, возвращает текст, chatId, userId */
    protected function hearsAndWriting($callable, $params = [])
    {
        if (isset($this->data->message->text)) {
            $callable([
                'text' => $this->data->message->text,
                'chat_id' => $this->data->message->chat->id,
                'user_id' => $this->data->message->from->id
            ]);
        }
    }

    /**
     * @param string|array $callable
     * @return void
     * @throws Exception
     */
    protected function isNewReplay($callable, $params = [])
    {

        $data  = ArrayHelper::toArray($this->data);
        if (ArrayHelper::getValue($data, 'message.reply_to_message')) {
            call_user_func($callable, $data);
        }
    }

    /**
     * @param string|array $callable
     * @return void
     * @throws Exception
     */
    protected function isNewTextMessage($callable, $params = [])
    {
        $data  = ArrayHelper::toArray($this->data);
        if (
            ArrayHelper::getValue($data, 'message.message_id') &&
            ArrayHelper::getValue($data, 'message.from.is_bot') !== true &&
            ArrayHelper::getValue($data, 'message.text') &&
            empty(ArrayHelper::getValue($data, 'message.reply_to_message'))
        ) {
            call_user_func($callable, $data);
        }
    }

    protected function isNewForwardMessageInBotChat($callable, $params = [])
    {
        $data  = ArrayHelper::toArray($this->data);
        $mFromId = ArrayHelper::getValue($data, 'message.from.id');
        $mChatId = ArrayHelper::getValue($data, 'message.chat.id');
        $mForwardFromId = ArrayHelper::getValue($data, 'message.forward_from.id');
        if (
            !empty($mFromId) &&
            !empty($mChatId) &&
            !empty($mForwardFromId) &&
            $mFromId === $mChatId
        ) {
            call_user_func($callable, $data, $params);
        }
    }

    protected function isNewMessageInBotChat($callable, $params = [])
    {
        $data  = ArrayHelper::toArray($this->data);
        $mFromId = ArrayHelper::getValue($data, 'message.from.id');
        $mChatId = ArrayHelper::getValue($data, 'message.chat.id');
        $mForwardFromId = ArrayHelper::getValue($data, 'message.forward_from.id');
        if (
            !empty($mFromId) &&
            !empty($mChatId) &&
            empty($mForwardFromId) &&
            $mFromId === $mChatId
        ) {
            call_user_func($callable, $data);
        }
    }
}
