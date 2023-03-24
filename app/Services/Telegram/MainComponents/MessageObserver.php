<?php

namespace App\Services\Telegram\MainComponents;

use App\Exceptions\TelegramException;
use App\Helper\ArrayHelper;
use App\Repositories\Community\CommunityRulesRepositoryContract;
use App\Repositories\Telegram\DTO\MessageDTO;
use App\Services\TelegramLogService;
use Illuminate\Log\Logger;
use Throwable;

class MessageObserver
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

    public function handleUserMessage($data)
    {
        $this->logger->debug('MessageObserver::handleUserMessage', $data);

        try {
            $dto = new MessageDTO();
            $dto->message_id = ArrayHelper::getValue($data,'message.message_id');
            $dto->telegram_user_id = ArrayHelper::getValue($data,'message.from.id');
            $dto->chat_id = ArrayHelper::getValue($data,'message.chat.id');
            $dto->telegram_date = ArrayHelper::getValue($data,'message.date');
            $dto->text = ArrayHelper::getValue($data,'message.text');

            $this->rulesRepository->checkRules($dto);

        } catch (Throwable $exception)
        {
            TelegramLogService::staticSendLogMessage($exception);
        }

    }
}