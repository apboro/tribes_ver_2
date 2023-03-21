<?php

namespace App\Logging;

use App\Models\TelegramBotActionLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class TelegramBotActionHandler extends AbstractProcessingHandler
{
    const START_BOT='startBot';
    const START_ON_GROUP = 'startOnGroup';
    const HELP_ON_CHAT='helpOnChat';
    const GET_TELEGRAM_USER_ID = 'getTelegramUserId';
    const SET_COMMAND='setCommand';
    const GET_CHAT_ID = 'getChatId';
    const GET_CHAT_TYPE = 'getChatType';
    const TARIFF_ON_USER = 'tariffOnUser';
    const TARIFF_ON_CHAT = 'tariffOnChat';
    const INLINE_COMMAND = 'inlineCommand';
    const INLINE_TARIFF_COMMAND = 'inlineTariffCommand';
    const DONATE_ON_CHAT = 'donateOnChat';
    const UNSUBSCRIBE = 'unsubscribe';
    const SAVE_FORWARD_MESSAGE_IN_BOT_CHAT_AS_QA='saveForwardMessageInBotChatAsQA';
    const ACCESS = 'access';
    CONST EXTEND = 'extend';
    const HELP_ON_BOT = 'helpOnBot';
    const DONATE_ON_USER = 'donateOnUser';
    const MATERIAL_AID = 'materialAid';
    const PERSONAL_AREA = 'personalArea';

    const FAQ = 'faq';
    const MY_SUBSCRIPTION = 'mySubscriptions';
    const SUBSCRIPTION_SEARCH = 'subscriptionSearch';
    const SET_TARIFF_FOR_USER_BY_PAID_ID = 'setTariffForUserByPayId';
    const KNOWLEDGE_SEARCH = 'knowledgeSearch';
    const SAVE_FORWARD_MESSAGE = 'saveForwardMessageInBotChatAsQA';
    const EVENT_NEW_CHAT_MEMBER = 'newChatMember';
    const EVENT_NEW_CHAT_USER = 'newChatUser';
    const EVENT_GROUP_CHAT_CREATED = 'groupChatCreated';
    const EVENT_CHANNEL_CHAT_CREATED = 'chanelChatCreated';
    const EVENT_CHECK_MEMBER = 'checkMember';
    const EVENT_NEW_CHAT_PHOTO = 'newChatPhoto';
    const EVENT_DELETE_CHAT = 'deleteChat';
    const EVENT_NEW_CHAT_TITLE = 'newChatTitle';
    const EVENT_DELETE_USER = 'deleteUser';
    const ACTION_SEND_HELLO_MESSAGE = 'send hello message';
    const ACTION_SEND_TELEGRAM_ID = 'send telegram id';
    const ACTION_SEND_CHAT_TELEGRAM_ID='send chat id';
    const ACTION_SEND_CHAT_TYPE = 'send chat type';
    const ACTION_SET_COMMAND = 'set command';
    const ACTION_SEND_TARIFF = 'send tariff';
    const ACTION_SEND_TARIFF_TO_CHAT= 'send tariff to chat';
    const SEND_HELP_IN_CHAT = 'send help in chat';
    const SEND_HELP_ON_BOT = 'send help in bot';
    const SEND_DONATE_IN_CHAT = 'send donate in chat';
    const SEND_DONATE_USER = 'send donate user';
    const SEND_SUBSCRIPTION_ID = 'send subscription id';
    const ACTION_SET_TERIFF_TO_USER = 'set tariff to user';
    const ACTION_SEND_MATERIAL_AID = 'send material aid';
    const ACTION_SEND_PERSONAL_AREA = 'send personal area';
    const ACTION_SEND_FAQ = 'send faq';
    const ACTION_SEND_MY_SUBSCRIPTION = 'send my subscription';
    const ACTION_SEND_KNOWLEDGE = 'send knowledge';
    const ACTION_SAVE_QUESTION_ANSWER = 'save_question_answer';
    const ACTION_SEND_ACCESS = 'send access';
    const ACTION_EXTEND = 'send extend';
    const ACTION_UNSUBSCRIBE = 'action unsubscribe';
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