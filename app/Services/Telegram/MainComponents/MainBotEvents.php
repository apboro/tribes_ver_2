<?php

namespace App\Services\Telegram\MainComponents;

use App;
use App\Domain\Entity\Telegram\TelegramConnectionEntity;
use App\Events\NewChatUserJoin;
use App\Exceptions\KnowledgeException;
use App\Helper\ArrayHelper;
use App\Jobs\DeleteTelegramMessage;
use App\Logging\TelegramBotActionHandler;
use App\Models\Captcha;
use App\Models\Community;
use App\Models\TelegramBotUpdateLog;
use App\Models\TelegramConnection;
use App\Models\TelegramUserList;
use App\Repositories\Community\CommunityRulesRepository;
use App\Repositories\Tariff\TariffRepositoryContract;
use App\Repositories\TelegramUserLists\TelegramUserListsRepositry;
use App\Services\Telegram;
use App\Services\Telegram\MainBot;
use App\Services\Telegram\TelegramMtproto\Event as ProtoEvents;
use App\Services\TelegramMainBotService;
use Askoldex\Teletant\Addons\Menux;
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
        'newChatUserWithCaptcha',
        'groupChatCreated',
        'chanelChatCreated',
        'makeBotAdmin',
        'makeUserBotAdmin',
        'userChangeStatus',
        'botChangeStatus',
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
            log::error('migrateToSuperGroup');
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }

    }

    /**
     * @return string
     */
    public function getAidedBotToGroupText(): string
    {
        $text = 'SpodialBot бот успешно добавлен в группу ' . $this->data->message->chat->title . "\n" .
            "\n" .
            'Для более детального сбора аналитики и возможностей модерации в личном кабинете spodial.com ' . "\n"
            . 'необходимо также добавить в группу бота ' . config('telegram_user_bot.user_bot.name') . "\n" .
            'с правами администратора.' . "\n"
            . "\n"
            . 'Инструкция по добавлению:' . "\n"
            . '1. Добавьте пользователя с ником ' . config('telegram_user_bot.user_bot.name') . ' в группу.'  . "\n"
            . '2. Назначьте пользователя администратором.';

        return $text;
    }

    /** Добавление бота в уже существующую ГРУППУ */
    protected function botAddedToGroup()
    {
        try {
            if (isset($this->data->message->new_chat_member->id)) {
                log::info('botAddedToGroup :    isset($this->data->message->new_chat_member->id) ');
                $chatId = $this->data->message->chat->id;
                $new_chat_member_id = $this->data->message->new_chat_member->id;
                if ($new_chat_member_id === $this->bot->botId) {
                    $this->bot->logger()->debug('Добавление бота в уже существующую ГРУППУ',
                        ['chat' => $chatId, 'tg' =>$this->data->message->from->id]);

                    $isBot = $this->data->message->from->is_bot;

                    if (!isset($this->data->message->from->username)) {
                        $this->data->message->from->username = '';
                    }

                    $isAnonymousBot = $this->data->message->from->username === 'GroupAnonymousBot';

                    if($isBot && $isAnonymousBot) {
                        log::info('Анонимный владелец группы');
                        return;
                    }

                    Telegram::botEnterGroupEvent(
                        $this->data->message->from->id,
                        $chatId,
                        $this->data->message->chat->type,
                        $this->data->message->chat->title,
                        $this->getPhoto($chatId)
                    );

                    TelegramConnectionEntity::initCompleted($this->data->message->from->id);

                    $message = $this->getAidedBotToGroupText();

                    $this->bot->getExtentionApi()->sendMess($this->data->message->from->id, $message);

                    Log::channel('telegram_bot_action_log')
                        ->log('info', '', [
                            'action' => TelegramBotActionHandler::EVENT_NEW_CHAT_MEMBER,
                            'event' => TelegramBotActionHandler::EVENT_NEW_CHAT_MEMBER,
                            'telegram_id' => $this->data->message->new_chat_member->id,
                            'chat_id' => $chatId
                        ]);
                }
            }
        } catch (Exception $e) {
            Log::error('botAddedToGroup');
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Добавление Юзербота в группу
     * @TODO after test fix logs
     */
    protected function userBotAddedToGroup()
    { 
        try {
            $str = json_encode($this->data, JSON_UNESCAPED_UNICODE);
//            if (isset($this->data->message->new_chat_member->id)) {
            if (isset($this->data->chat_member->new_chat_member->user->id)) {
                $chatId = $this->data->chat_member->chat->id;
                $botId = $this->data->chat_member->new_chat_member->user->id;
                $mainBotId = config('telegram_user_bot.user_bot.id');
                if ($botId === $mainBotId) {
                    log::info('User Bot Added To Group');
                    log::info('$this->data' . $str);
                    log::info('config(telegram_user_bot.user_bot.id)' . $mainBotId);
                    $this->bot->logger()->debug('Добавление Юзер Бота в уже существующую ГРУППУ', ArrayHelper::toArray($this->data->chat_member->chat));
                    Telegram::userBotEnterGroupEvent(
                        $this->data->chat_member->from->id,
                        $chatId,
                        $this->data->chat_member->chat->type,
                        $this->data->chat_member->chat->title
                    );
                    Log::channel('telegram_bot_action_log')->
                        log('info', '', [
                            'action' => TelegramBotActionHandler::EVENT_USER_BOT_ADDED,
                            'event' => TelegramBotActionHandler::EVENT_USER_BOT_ADDED,
                            'telegram_id' => $this->data->chat_member->new_chat_member->user->id,
                            'chat_id' => $chatId
                        ]);
                }
            }
        } catch (Exception $e) {
            Log::error('Добавление Юзербота в группу');
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    private function newChatUserWithCaptcha()
    {
        if (isset($this->data->callback_query)
            && $this->data->callback_query->data === "captcha_button") {
            $member = $this->data->callback_query->from;
            $chatId = $this->data->callback_query->message->chat->id;
            Log::info('New chat user with captcha, $member', [$member]);

            $community = Community::whereHas('connection', function ($q) use ($chatId) {
                $q->where('chat_id', $chatId);
            })->first();
            $captcha = Captcha::where('telegram_user_id', $member->id)
                ->where('chat_id', $chatId)
                ->where('solved', false)
                ->first();
            if ($captcha) {
                $captcha->solved = true;
                $captcha->save();
                $this->registerNewUser($member, $community, $chatId);
                $this->bot->getExtentionApi()
                    ->deleteUserMessage($captcha->message_id, $chatId);
            }
        }
    }

    protected function registerNewUser($member, $community, $chatId)
    {
        $userName = !empty($member->username) ? $member->username : '';
        $firstName = !empty($member->first_name) ? $member->first_name : '';
        $lastName = !empty($member->last_name) ? $member->last_name : '';

        $ty = Telegram::registerTelegramUser($member->id, NULL, $userName, $firstName, $lastName);
        Log::debug('New user registered', [$ty]);
        $tyWasInCommunityBefore = $ty->communities()->find($community->id);

        if ($tyWasInCommunityBefore) {
            $ty->communities()->updateExistingPivot($community->id,
                [
                    'exit_date' => null,
                    'accession_date' => time()
                ]);
        } else {
            $ty->communities()->attach($community, [
                'role' => 'member',
                'accession_date' => time()
            ]);
        }
       
        if ($member->id == config('telegram_user_bot.user_bot.id') || $member->id == config('telegram_bot.bot.botId') ) {
            Telegram::addUserToWhiteList($community->id, $member->id);
        }

        $this->sendGreeting($chatId, $community);
        Event::dispatch(new NewChatUserJoin($chatId, $member->id));
    }

    protected function sendGreeting($chatId, $community)
    {
        if ($onboarding = $community->onboardingRule) {
            $image = null;
            if ($onboarding->greeting_image) {
                $path = env('APP_URL') . '/storage/' . $community->onboardingRule->greeting_image;
                $image = "<a href='$path'>&#160</a>";
            }
            $onboarding = json_decode($onboarding->rules, true);
            if (isset($onboarding['greetings'])) {
                $description = strip_tags(str_replace('<br>', "\n", $onboarding['greetings']['content']));
                $text = $description . $image;
                Log::debug('Send greeting', [$onboarding]);
                $mess = $this->bot->getExtentionApi()->sendMessWithReturn($chatId, $text);

                if ($onboarding['deleteGreetings']) {
                    DeleteTelegramMessage::dispatch($chatId, $mess['result']['message_id'])
                        ->delay($onboarding['deleteGreetings']['duration'])->onConnection('redis');
                }
            }
        }
    }

    /** Новый пользователь */
    protected function newChatUser()
    {
        try {
            $member = $this->data->message->new_chat_member ?? null;
            if (!$member) {
                $member = $this->data->chat_member->new_chat_member->user ?? null;
            }

            $newMemberId = $member->id ?? null;

            if ($member && $newMemberId !== $this->bot->botId) {
                $chatId = $this->data->message->chat->id ?? $this->data->chat_member->chat->id;
                Log::info('Новый пользователь newChatUser()');
                Log::channel('telegram_bot_action_log')->log('info', '', [
                    'event' => TelegramBotActionHandler::EVENT_NEW_CHAT_USER,
                    'telegram_id' => $newMemberId,
                    'chat_id' => $chatId
                ]);

                $community = Community::whereHas('connection', function ($q) use ($chatId) {
                    $q->where('chat_id', $chatId);
                })->first();

                if ($community) {
                    $onboarding = $community->onboardingRule;
                    if ($onboarding) {
                        $onboarding = json_decode($onboarding->rules, true);
                    }
                    if (isset($onboarding['chatJoinAction']) && $onboarding['chatJoinAction']['type'] === 'captcha') {
                        Log::debug('onboarding in newUser', [$onboarding]);
                        $keyboard = [[[
                            'text' => 'Вступить в группу',
                            'callback_data' => 'captcha_button'
                        ]]];
                        $message = $this->bot->getExtentionApi()
                            ->sendMessWithReturn($chatId, 'Нажмите кнопку для вступления', false, $keyboard);
                        Captcha::updateOrCreate(['chat_id' => $chatId, 'telegram_user_id' => $newMemberId],
                            [
                                'solved' => false,
                                'message_id' => $message['result']['message_id'],
                                'username' => $member->username ?? '',
                                'first_name' => $member->first_name ?? '',
                                'last_name' => $member->last_name ?? '',
                            ]);
                        exit;
                    } else {
                        $this->registerNewUser($member, $community, $chatId);
                    }
                }
            }
        } catch
        (Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Добавление бота в новую ГРУППУ */
    protected
    function groupChatCreated()
    {
        try {
            if (isset($this->data->message->group_chat_created)) {
                log::info('_______________groupChatCreated________' , [$this->data]);
                if ($this->data->message->group_chat_created == true) {
                    $chatId = $this->data->message->chat->id;
                    Log::debug('Создание Группы С Ботом', [$chatId]);
                    Telegram::botEnterGroupEvent(
                        $this->data->message->from->id,
                        $chatId,
                        $this->data->message->chat->type,
                        $this->data->message->chat->title,
                        $this->getPhoto($chatId)
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
            Log::error('Добавление бота в новую ГРУППУ');
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Добавление бота в новый канал */
    protected
    function chanelChatCreated()
    {
        try {
            if (isset($this->data->my_chat_member)) {
                $chatType = $this->data->my_chat_member->chat->type;
                $status = $this->data->my_chat_member->new_chat_member->status;

                if ($chatType === 'channel' and $status !== 'left') {
                    log::info('Добавление бота в новый канал');
//                    $this->bot->logger()->debug('Добавление бота в новый канал', ArrayHelper::toArray($this->data));

                    $chatId = $this->data->my_chat_member->chat->id;
                    Telegram::botEnterGroupEvent(
                        $this->data->my_chat_member->from->id,
                        $chatId,
                        $this->data->my_chat_member->chat->type,
                        $this->data->my_chat_member->chat->title,
                        $this->getPhoto($chatId)
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
                        $chatId,
                        $this->data
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
    protected function makeUserBotAdmin()
    {
        try {
            $str = json_encode($this->data, JSON_UNESCAPED_UNICODE);
            //log::info('$this->data' . $str);
            if (isset($this->data->chat_member)) {
                if (
                    $this->data->chat_member->new_chat_member->user->id == config('telegram_user_bot.user_bot.id') &&
                    $this->data->chat_member->new_chat_member->status == 'administrator'
                ) {
                    $this->bot->logger()->debug('User Бот в группе стал администратором +1', ArrayHelper::toArray($this->data));
                    $chatId = $this->data->chat_member->chat->id;
                    Telegram::userBotGetPermissionsEvent(
                        $this->data->chat_member->from->id,
                        $this->data->chat_member->new_chat_member->status,
                        $chatId
                    );

                    /** @var ProtoEvents $events */
                    $events = App::make(Telegram\TelegramMtproto\Event::class);
                    $events->handler($str);

                    Log::channel('telegram_bot_action_log')->
                    log('info', '', [
                        'event' => TelegramBotActionHandler::USER_BOT_GET_ADMIN,
                        'chat_id' => $chatId,
                        'telegram_id' => $this->data->chat_member->from->id
                    ]);
                } else {
                    log::debug('my_chat_member not ' . config('telegram_user_bot.user_bot.id'));
                    log::debug('new_chat_member->status  not administrator');
                }
            }
        } catch (Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /**
     *  Событие изменения статуса пользователя (администратор, участник...) 
     * 
     * @return void
     */
    protected function userChangeStatus(): void
    {
        try {
            $userId = $this->data->chat_member->new_chat_member->user->id ?? null;
            $newStatus = $this->data->chat_member->new_chat_member->status ?? null;
            $oldStatus = $this->data->chat_member->old_chat_member->status ?? null;
            $chatId = $this->data->chat_member->chat->id ?? null;

            if ($userId && $newStatus && $oldStatus && $chatId) {
                $mainBotId  = config('telegram_user_bot.user_bot.id');
                $isDifferentStatus = $newStatus != $oldStatus;
                $isNotChatBot = $userId != $this->bot->botId;
                $isNotUserBot = $userId != $mainBotId;
                if ($isNotUserBot && $isNotChatBot && $isDifferentStatus) {
                    Log::info('Событие изменения статуса пользователя userChangeStatus()');
                    Telegram::userChangeStatus($userId, $chatId, $newStatus, $oldStatus);
                }
            }
        } catch (Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** 
     * Событие изменения статуса чат бота (администратор, участник...)  
     * 
     * @return void
     */
    protected function botChangeStatus(): void
    {
        try {
            $chatMember = $this->data->my_chat_member ?? null;
            $userId = $chatMember->new_chat_member->user->id ?? null;
            $newStatus = $chatMember->new_chat_member->status ?? null;
            $oldStatus = $chatMember->old_chat_member->status ?? null;
            $chatId = $chatMember->chat->id ?? null;

            if ($userId && $oldStatus && $newStatus  && $chatId) {
                if ($userId == $this->bot->botId && $oldStatus != $newStatus) {
                    Telegram::botChangeStatus($this->bot->botId, $chatId, $newStatus, $oldStatus);
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
                Log::info('Событие изменения или добавления фотографии группы или канала newChatPhoto()');
                $chatId = $chat->chat->id;
                $telegramId = $chat->from->id ?? 0;
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
                    'chat_id' => $chatId,
                    'telegram_id' => $telegramId,
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
                    Telegram::removeAllAdminAndCreatorFromWhiteList($this->data->my_chat_member->chat->id);
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
            $userId = null;
            $chatId = null;
            // Уведомление chat_member
            if (isset($this->data->chat_member)) {
                $oldStatus = $this->data->chat_member->old_chat_member->status ?? null;
                $newStatus = $this->data->chat_member->new_chat_member->status ?? null;
                if ($oldStatus == 'member' && $newStatus == 'left') {
                        $userId = $this->data->chat_member->new_chat_member->user->id ?? null;
                        $chatId = $this->data->chat_member->chat->id ?? null;
                    }
            }
            // Сообщение message
            if (isset($this->data->message) && isset($this->data->message->left_chat_member)) {
                $userId = $this->data->message->left_chat_member->id ?? null;
                $chatId = $this->data->message->chat->id ?? null;
            }
            
            if ($userId && $chatId && $userId  != config('telegram_bot.bot.botId')) {
                    Log::info('Событие удаления пользователя из группы deleteUser()');
                    $telegram = new Telegram(app(TariffRepositoryContract::class));
                    $this->bot->logger()->debug('Delete user with:', [$chatId, $userId]);
                    $telegram->deleteUser($chatId, $userId);
                    Log::channel('telegram_bot_action_log')->
                        log('info', '', [
                            'event' => TelegramBotActionHandler::EVENT_DELETE_USER,
                            'telegram_id' => $userId,
                            'chat_id' => $chatId
                        ]);
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
                Log::info('Событие изменения названия группы или канала newChatTitle()');
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
            Log::debug('newChatTitle err ' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
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
            if (!$photoId) {
                return '/images/no-image.svg';
            }

            $photoPath = $this->bot->Api()->getFile([
                'file_id' => $photoId
            ])->filePath() ?? NULL;
            if (!$photoPath) {
                return '/images/no-image.svg';
            }

            return env('TELEGRAM_BASE_URL') . '/file/bot' . $this->bot->getToken() . '/' . $photoPath;
        } catch (Exception $e) {
            Log::error('ERORR getPhoto ' . $e);
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
