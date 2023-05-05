<?php

namespace App\Services\Telegram\MainComponents;

use App\Exceptions\TelegramException;
use App\Helper\ArrayHelper;
use App\Repositories\Community\CommunityRulesRepositoryContract;
use App\Repositories\Telegram\DTO\MessageDTO;
use App\Services\TelegramLogService;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;
use Throwable;

class RulesObserver
{

    private Logger $logger;
    private CommunityRulesRepositoryContract $rulesRepository;

    public function __construct(
        CommunityRulesRepositoryContract $rulesRepository,
        Logger                           $logger
    )
    {
        $this->rulesRepository = $rulesRepository;
        $this->logger = $logger;
    }

    public function handleRules($data)
    {
        $this->logger->debug('RulesObserver::handleRules', $data);

        try {
            $this->logger->debug('Before Message DTO', [$data]);
            $dto = new MessageDTO();
            $dto->message_id = ArrayHelper::getValue($data,'message.message_id');
            $dto->telegram_user_id = ArrayHelper::getValue($data,'message.from.id');
            $dto->telegram_user_first_name = ArrayHelper::getValue($data,'message.from.first_name');
            $dto->telegram_user_last_name = ArrayHelper::getValue($data,'message.from.last_name');
            $dto->telegram_user_username = ArrayHelper::getValue($data,'message.from.username');
            $dto->chat_id = ArrayHelper::getValue($data,'message.chat.id');
            $dto->telegram_date = ArrayHelper::getValue($data,'message.date');
            $dto->text = ArrayHelper::getValue($data,'message.text');
            $dto->message_entities = ArrayHelper::getValue($data,'message.entities');
            $dto->forward = ArrayHelper::getValue($data,'message.forward_date');
            $dto->new_chat_member_id = ArrayHelper::getValue($data,'message.new_chat_member.id');
            $dto->new_chat_member_bot = ArrayHelper::getValue($data,'message.new_chat_member.is_bot');

            $this->logger->debug('Message DTO ready', [$dto]);
            $this->rulesRepository->handleRules($dto);

        } catch (Throwable $e){
            $this->logger->error('RulesObserver::handleRules exception', ['Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile()]);
        }
    }

}