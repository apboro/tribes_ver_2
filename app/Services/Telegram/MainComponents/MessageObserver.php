<?php

namespace App\Services\Telegram\MainComponents;

use App\Exceptions\TelegramException;
use App\Helper\ArrayHelper;
use App\Repositories\Telegram\DTO\MessageDTO;
use App\Repositories\Telegram\TeleMessageRepositoryContract;
use Illuminate\Log\Logger;
use Throwable;

class MessageObserver
{


    private TeleMessageRepositoryContract $messageRepository;
    private Logger $logger;

    public function __construct(
        TeleMessageRepositoryContract $messageRepository,
        Logger                        $logger
    )
    {

        $this->messageRepository = $messageRepository;
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

            $this->messageRepository->saveMessageForChat($dto);

        } catch (Throwable $exception)
        {
            new TelegramException($exception->getMessage(),[
                'data' => $data,
            ],$exception->getPrevious());
        }

    }
}