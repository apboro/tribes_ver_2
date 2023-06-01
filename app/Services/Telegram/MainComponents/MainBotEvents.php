<?php

namespace App\Services\Telegram\MainComponents;

use App\Events\NewChatUserJoin;
use App\Exceptions\KnowledgeException;
use App\Helper\ArrayHelper;
use App\Logging\TelegramBotActionHandler;
use App\Models\Community;
use App\Models\TelegramBotUpdateLog;
use App\Models\TelegramConnection;
use App\Models\TelegramUserList;
use App\Repositories\Community\CommunityRulesRepository;
use App\Repositories\Tariff\TariffRepositoryContract;
use App\Repositories\TelegramUserLists\TelegramUserListsRepositry;
use App\Services\Telegram;
use App\Services\Telegram\MainBot;
use App\Services\TelegramMainBotService;
use Askoldex\Teletant\Context;
use Exception;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class MainBotEvents
{
    protected MainBot $bot;
    protected ?object $data;


    public function __construct(MainBot $bot, ?object $data)

    {
        $this->bot = $bot;
        $this->data = $data;
    }

    public function initEventsMainBot(array $config = [
        'migrateToSuperGroup',
        'removeUserBotFromChat',
        'botAddedToGroup',
        'userBotAddedToGroup',
        'newChatUser',
        'groupChatCreated',
        'chanelChatCreated',
        'makeBotAdmin',
        'makeUserBotAdmin',
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


    public function migrateToSuperGroup()
    {
        try {
            if (isset($this->data->message->migrate_to_chat_id)) {
                $oldChatId = $this->data->message->chat->id;
                $newChatId = $this->data->message->migrate_to_chat_id;
                $this->bot->logger()->debug('превращение в супергруппу c id: ', ArrayHelper::toArray($this->data->message->migrate_to_chat_id));
                $connection = TelegramConnection::query()->where('chat_id', $oldChatId)->first();
                $connection->chat_id = $newChatId;
                $connection->chat_type = 'supergroup';
                $connection->save();
            }
        } catch (Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }

    }

    /** Добавление бота в уже существующую ГРУППУ */
    protected function botAddedToGroup()
    {
        try {
            if (isset($this->data->message->new_chat_member->id)) {
                $chatId = $this->data->message->chat->id;
                $new_chat_member_id = $this->data->message->new_chat_member->id;
                if ($new_chat_member_id == $this->bot->botId) {
                    $this->bot->logger()->debug('Добавление бота в уже существующую ГРУППУ', ['chat'=>$chatId]);
                    Telegram::botEnterGroupEvent(
                        $this->data->message->from->id,
                        $chatId,
                        $this->data->message->chat->type,
                        $this->data->message->chat->title,
//                        $this->getPhoto($chatId)
                    );
                    Log::channel('telegram_bot_action_log')
                        ->
                        log('info', '', [
                            'action' => TelegramBotActionHandler::EVENT_NEW_CHAT_MEMBER,
                            'event' => TelegramBotActionHandler::EVENT_NEW_CHAT_MEMBER,
                            'telegram_id' => $this->data->message->new_chat_member->id,
                            'chat_id' => $chatId
                        ]);
                }
            }
        } catch (Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Добавление Юзербота в группу */
    protected function userBotAddedToGroup()
    {
        try {
            if (isset($this->data->message->new_chat_member->id)) {
                $chatId = $this->data->message->chat->id;
                if ($this->data->message->new_chat_member->id == config('telegram_user_bot.user_bot.id')) {
                    $this->bot->logger()->debug('Добавление Юзер Бота в уже существующую ГРУППУ', ArrayHelper::toArray($this->data->message->chat));
                    Telegram::userBotEnterGroupEvent(
                        $this->data->message->from->id,
                        $chatId,
                        $this->data->message->chat->type,
                        $this->data->message->chat->title
                    );
                    Log::channel('telegram_bot_action_log')
                        ->
                        log('info', '', [
                            'action' => TelegramBotActionHandler::EVENT_USER_BOT_ADDED,
                            'event' => TelegramBotActionHandler::EVENT_USER_BOT_ADDED,
                            'telegram_id' => $this->data->message->new_chat_member->id,
                            'chat_id' => $chatId
                        ]);
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
                    $new_member_id = $this->data->message->new_chat_member->id;

                    Log::channel('telegram_bot_action_log')->
                    log('info', '', [
                        'event' => TelegramBotActionHandler::EVENT_NEW_CHAT_USER,
                        'telegram_id' => $new_member_id,
                        'chat_id' => $chatId
                    ]);
                    $community = Community::whereHas('connection', function ($q) use ($chatId) {
                        $q->where('chat_id', $chatId);
                    })->first();

//                    $this->bot->onUpdate('new_chat_member', function(Context $ctx){
//                        Log::debug('new_chat_member', [$ctx]);
//                    });

                    if ($community) {

                        $member = $this->data->message->new_chat_member;
                        if (!empty($member->username) || !empty($member->first_name)) {

                            $userName = !empty($member->username) ? $member->username : '';
                            $firstName = !empty($member->first_name) ? $member->first_name : '';
                            $lastName = !empty($member->last_name) ? $member->last_name : '';

                            $ty = Telegram::registerTelegramUser($member->id, NULL, $userName, $firstName, $lastName);

                            $tyWasInCommunityBefore = $ty->communities()->find($community->id);

                            if ($tyWasInCommunityBefore) {
                                $ty->communities()->updateExistingPivot($community->id, ['exit_date' => null]);
                            } else {
                                $ty->communities()->attach($community, [
                                    'role' => 'member',
                                    'accession_date' => time()
                                ]);
                            }

                            if ($onboarding = $community->onboardingRule) {
                                $image= null;
                                if ($onboarding->greeting_image) {
                                    $path = env('APP_URL') . '/storage/' . $community->onboardingRule->greeting_image;
                                    $image = "<a href='$path'>&#160</a>";
                                }
                                $onboarding = json_decode($onboarding->rules, true);
                                $description = strip_tags(str_replace('<br>', "\n", $onboarding['greetings']['content']));
                                $text = $description . $image;
                                $this->bot->getExtentionApi()->sendMess($chatId, $text);
                            }
                        }
                        Event::dispatch(new NewChatUserJoin($chatId, $new_member_id));
                    }
                }
            }
        } catch (Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Добавление бота в новую ГРУППУ */
    protected
    function groupChatCreated()
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
                    // $this->getPhoto($chatId)
                    );
                    Log::channel('telegram_bot_action_log')->
                    log('info', '', [
                        'event' => TelegramBotActionHandler::EVENT_GROUP_CHAT_CREATED,
                        'chat_id' => $chatId,
                        'telegram_id' => $this->data->message->from->id
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Добавление бота в новый канал */
    protected
    function chanelChatCreated()
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
//                        $this->getPhoto($chatId)
                    );
                    Log::channel('telegram_bot_action_log')->
                    log('info', '', [
                        'event' => TelegramBotActionHandler::EVENT_CHANNEL_CHAT_CREATED,
                        'chat_id' => $chatId
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Событие назначения бота администратором в ГРУППЕ */
    protected
    function makeBotAdmin()
    {
        try {
            if (isset($this->data->my_chat_member)) {
                if (
                    $this->data->my_chat_member->new_chat_member->user->id == $this->bot->botId &&
                    $this->data->my_chat_member->new_chat_member->status == 'administrator'
                ) {
                    $this->bot->logger()->debug('Бот в группе стал администратором', ArrayHelper::toArray($this->data));
                    $chatId = $this->data->my_chat_member->chat->id;
                    Telegram::botGetPermissionsEvent(
                        $this->data->my_chat_member->from->id,
                        $this->data->my_chat_member->new_chat_member->status,
                        $chatId
                    );
                    Log::channel('telegram_bot_action_log')->
                    log('info', '', [
                        'event' => TelegramBotActionHandler::EVENT_CHECK_MEMBER,
                        'chat_id' => $chatId,
                        'telegram_id' => $this->data->my_chat_member->from->id
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Событие назначения User бота администратором в ГРУППЕ */
    protected
    function makeUserBotAdmin()
    {
        try {
            if (isset($this->data->my_chat_member)) {
                if (
                    $this->data->my_chat_member->new_chat_member->user->id == config('telegram_user_bot.user_bot.id') &&
                    $this->data->my_chat_member->new_chat_member->status == 'administrator'
                ) {
                    $this->bot->logger()->debug('User Бот в группе стал администратором', ArrayHelper::toArray($this->data));
                    $chatId = $this->data->my_chat_member->chat->id;
                    Telegram::userBotGetPermissionsEvent(
                        $this->data->my_chat_member->from->id,
                        $this->data->my_chat_member->new_chat_member->status,
                        $chatId
                    );
                    Log::channel('telegram_bot_action_log')->
                    log('info', '', [
                        'event' => TelegramBotActionHandler::USER_BOT_GET_ADMIN,
                        'chat_id' => $chatId,
                        'telegram_id' => $this->data->my_chat_member->from->id
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Событие изменения или добавления фотографии группы или канала*/
    protected
    function newChatPhoto()
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
                Log::channel('telegram_bot_action_log')->
                log('info', '', [
                    'event' => TelegramBotActionHandler::EVENT_NEW_CHAT_PHOTO,
                    'chat_id' => $chatId
                ]);
            }
        } catch (Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Событие удаления бота из чата или удаление чата */
    protected
    function deleteChat()
    {
        try {
            if (isset($this->data->my_chat_member)) {
                if (
                    $this->data->my_chat_member->new_chat_member->user->id == $this->bot->botId &&
                    ($this->data->my_chat_member->new_chat_member->status == 'left' ||
                        $this->data->my_chat_member->new_chat_member->status == 'kicked')
                ) {
                    $this->bot->logger()->debug('bot kicked, delete chat', ArrayHelper::toArray($this->data->my_chat_member->new_chat_member));
                    Log::channel('telegram_bot_action_log')->
                    log('info', '', [
                        'event' => TelegramBotActionHandler::EVENT_DELETE_CHAT,
                        'chat_id' => $this->data->my_chat_member->chat->id
                    ]);
                    Telegram::deactivateCommunity($this->data->my_chat_member->chat->id);
                }
            }
        } catch (Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Событие удаления юзербота из чата*/
    protected
    function removeUserBotFromChat()
    {
        try {
            if (isset($this->data->my_chat_member)) {
                if (
                    $this->data->my_chat_member->new_chat_member->user->id == config('telegram_user_bot.user_bot.id') &&
                    ($this->data->my_chat_member->new_chat_member->status == 'left' ||
                        $this->data->my_chat_member->new_chat_member->status == 'kicked')
                ) {
                    $this->bot->logger()->debug('userbot kicked', ArrayHelper::toArray($this->data->my_chat_member->new_chat_member));
                    Log::channel('telegram_bot_action_log')->
                    log('info', '', [
                        'event' => TelegramBotActionHandler::EVENT_USER_BOT_KICKED,
                        'chat_id' => $this->data->my_chat_member->chat->id
                    ]);
                    Telegram::removeUserBot($this->data->my_chat_member->chat->id, $this->data->message->from->id);
                }
            }
        } catch (Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Событие удаления пользователя из группы */
    protected
    function deleteUser()
    {
        try {
            if (isset($this->data->message->left_chat_member)) {
                if ($this->data->message->left_chat_member->id != env('TELEGRAM_BOT_ID')) {
                    $telegram = new Telegram(app(TariffRepositoryContract::class));
                    $this->bot->logger()->debug('Delete user with:', [$this->data->message->chat->id, $this->data->message->left_chat_member->id]);
                    $telegram->deleteUser($this->data->message->chat->id, $this->data->message->left_chat_member->id);
                    Log::channel('telegram_bot_action_log')->
                    log('info', '', [
                        'event' => TelegramBotActionHandler::EVENT_DELETE_USER,
                        'telegram_id' => $this->data->message->left_chat_member->id,
                        'chat_id' => $this->data->message->chat->id
                    ]);
                }
            }
        } catch (Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Событие изменения названия группы или канала */
    protected
    function newChatTitle()
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
                Log::channel('telegram_bot_action_log')->log('info', '', [
                        'event' => TelegramBotActionHandler::EVENT_NEW_CHAT_TITLE,
                        'chat_id' => $community->chat->id
                    ]);
                Telegram::newTitle(
                    $community->chat->id,
                    $community->new_chat_title
                );
            }
        } catch (Exception $e) {
            Log::debug('newChatTitle err '. $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Возвращает путь для СКАЧИВАНИЯ аватарки чата
     * @param $chatId
     * @return string
     * @throws \Askoldex\Teletant\Exception\TeletantException
     */
    protected
    function getPhoto($chatId)
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
            Log::error($e);
        }
    }

    /**
     * @param string|array $callable
     * @return void
     * @throws Exception
     */
    protected
    function isNewReplay($callable, $params = [])
    {

        $data = ArrayHelper::toArray($this->data);
        if (ArrayHelper::getValue($data, 'message.reply_to_message')) {
            call_user_func($callable, $data);
        }
    }

    /**
     * @param string|array $callable
     * @return void
     * @throws Exception
     */
    protected
    function isNewTextMessage($callable, $params = [])
    {
        $data = ArrayHelper::toArray($this->data);
        if (
            ArrayHelper::getValue($data, 'message.message_id') &&
            ArrayHelper::getValue($data, 'message.from.is_bot') !== true &&
            ArrayHelper::getValue($data, 'message.text') &&
            empty(ArrayHelper::getValue($data, 'message.reply_to_message'))
        ) {
            call_user_func($callable, $data);
        }
    }

    protected
    function isNewForwardMessageInBotChat($callable, $params = [])
    {
        $data = ArrayHelper::toArray($this->data);
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

    protected
    function isSetRules($callable, $params = [])
    {
        $data = ArrayHelper::toArray($this->data);
        call_user_func($callable, $data);
    }

    protected
    function isNewMessageInBotChat($callable, $params = [])
    {
        $data = ArrayHelper::toArray($this->data);
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
