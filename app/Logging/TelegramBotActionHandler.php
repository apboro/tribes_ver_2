<?php

namespace App\Logging;

use App\Models\TelegramBotActionLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class TelegramBotActionHandler extends AbstractProcessingHandler
{
    const START_BOT='Команда /start отпавлена боту';
    const START_ON_GROUP = 'Команда /start отправлена в группу';
    const HELP_ON_CHAT='Команда /help отправлена в группу';
    const GET_TELEGRAM_USER_ID = 'Узнать Telegram ID';
    const SET_COMMAND='setCommand';
    const GET_CHAT_ID = 'Узнать ID чата';
    const GET_CHAT_TYPE = 'getChatType';
    const TARIFF_ON_USER = 'Команда /tariff отпавлена боту';
    const TARIFF_ON_CHAT = 'Команда /tariff отправлена в группу';
    const INLINE_COMMAND = 'inlineCommand';
    const INLINE_TARIFF_COMMAND = 'inlineTariffCommand';
    const DONATE_ON_CHAT = 'Команда /donate отправлена в группу';
    const UNSUBSCRIBE = 'Отписаться';
    const SAVE_FORWARD_MESSAGE_IN_BOT_CHAT_AS_QA='Сохранить вопрос-ответ';
    const ACCESS = 'access';
    CONST EXTEND = 'extend';
    const HELP_ON_BOT = 'Команда /help отпавлена боту';
    const DONATE_ON_USER = 'Команда /donate отпавлена боту';
    const MATERIAL_AID = 'Материальная помощь';
    const PERSONAL_AREA = 'personalArea';

    const FAQ = 'faq';
    const MY_SUBSCRIPTION = 'Вызов команды "Мои подписки"';
    const SUBSCRIPTION_SEARCH = 'Поиск подписки';
    const SET_TARIFF_FOR_USER_BY_PAID_ID = 'setTariffForUserByPayId';
    const KNOWLEDGE_SEARCH = 'Поиск по базе знаний';
    const SAVE_FORWARD_MESSAGE = 'saveForwardMessageInBotChatAsQA';
    const EVENT_NEW_CHAT_MEMBER = 'Бот добавлен в группу';
    const EVENT_NEW_CHAT_USER = 'Новый пользователь в группе';
    const EVENT_GROUP_CHAT_CREATED = 'Создание группы';
    const EVENT_CHANNEL_CHAT_CREATED = 'Создание канала';
    const EVENT_CHECK_MEMBER = 'Бот стал админом в группе';
    const EVENT_NEW_CHAT_PHOTO = 'Новое фото группы';
    const EVENT_DELETE_CHAT = 'Удаление группы';
    const EVENT_NEW_CHAT_TITLE = 'Новое название группы';
    const EVENT_DELETE_USER = 'Удаление участника';
    const ACTION_SEND_HELLO_MESSAGE = 'Отправлено приветственное сообщение';
    const ACTION_SEND_TELEGRAM_ID = 'Отправлен telegram id';
    const ACTION_SEND_CHAT_TELEGRAM_ID='Отправлен chat id';
    const ACTION_SEND_CHAT_TYPE = 'send chat type';
    const ACTION_SET_COMMAND = 'set command';
    const ACTION_SEND_TARIFF = 'Отправлены тарифы';
    const ACTION_SEND_TARIFF_TO_CHAT= 'Отправлены тарифы в чат группы';
    const SEND_HELP_IN_CHAT = 'Отправлена команда /help в чат группы';
    const SEND_HELP_ON_BOT = 'Отправлена команда /help боту';
    const SEND_DONATE_IN_CHAT = 'Отправлена команда /donate в чат группы';
    const SEND_DONATE_USER = 'Участник отправил пожертвование';
    const SEND_SUBSCRIPTION_ID = 'Отправлено id подписки';
    const ACTION_SET_TERIFF_TO_USER = 'Установить тариф участнику';
    const ACTION_SEND_MATERIAL_AID = 'Отправить материальную помощь';
    const ACTION_SEND_PERSONAL_AREA = 'send personal area';
    const ACTION_SEND_FAQ = 'Команда faq';
    const ACTION_SEND_MY_SUBSCRIPTION = 'Команда "Мои подписки';
    const ACTION_SEND_KNOWLEDGE = 'Команда вызова базы знаний';
    const ACTION_SAVE_QUESTION_ANSWER = 'Сохраненик вопрос-ответ';
    const ACTION_SEND_ACCESS = 'send access';
    const ACTION_EXTEND = 'send extend';
    const ACTION_UNSUBSCRIBE = 'Команда Отписаться';
    public function __construct($level = Logger::DEBUG, $bubble = true) {
        parent::__construct($level, $bubble);
    }
    protected function write(array $record):void
    {
        try {
            TelegramBotActionLog::create([
                'event'=>$record['context']['event'] ?? null,
                'action'=>$record['context']['action'] ?? null,
                'telegram_id'=> $record['context']['telegram_id'] ?? null,
                'chat_id'=> $record['context']['chat_id'] ?? null
            ]);
        }catch (\Exception $exception){
            Log::channel('emergency')->log('error','Undefined type');
        }

    }

}