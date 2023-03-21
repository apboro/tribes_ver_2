<?php

namespace App\Logging;

use App\Models\TelegramBotActionLog;
use Carbon\Carbon;
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

    public function __construct($level = Logger::DEBUG, $bubble = true) {
        parent::__construct($level, $bubble);
    }
    protected function write(array $record):void
    {
         TelegramBotActionLog::create([
             'type'=>$record['context']['action'],
             'telegram_id'=> $record['context']['telegram_id'],
             'chat_id'=> $record['context']['chat_id']
         ]);
    }

}