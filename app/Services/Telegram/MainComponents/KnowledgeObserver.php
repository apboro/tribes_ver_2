<?php

namespace App\Services\Telegram\MainComponents;

use App\Exceptions\TelegramException;
use App\Helper\ArrayHelper;
use App\Repositories\Community\CommunityRepositoryContract;
use App\Services\Knowledge\ManageQuestionService;
use Illuminate\Log\Logger;
use Illuminate\Support\Str;
use Throwable;

class KnowledgeObserver
{
    private CommunityRepositoryContract $communityRepository;
    private ManageQuestionService $manageQuestionService;
    private Logger $logger;

    public function __construct(
        CommunityRepositoryContract $communityRepository,
        ManageQuestionService       $manageQuestionService,
        Logger                      $logger
    )
    {
        $this->communityRepository = $communityRepository;
        $this->manageQuestionService = $manageQuestionService;
        $this->logger = $logger;
    }

    /**
     * ответ автора на сообщение другого пользователя в чате инициируети создание пары вопрос ответ
     * ограничивается командой /qas
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public function handleAuthorReply($data): bool
    {
        $this->logger->debug('author replay', $data);
        $replyTeleUserId = ArrayHelper::getValue($data, 'message.from.id', 0);
        $chatId = ArrayHelper::getValue($data, 'message.chat.id', 0);
        if (!$this->communityRepository->isChatBelongsToTeleUserId($chatId, $replyTeleUserId)) {
            $this->logger->debug('чат не принадлежит этому пользователю', [
                'chat' => $chatId,
                'replyTeleUserId' => $replyTeleUserId
            ]);
            return false;
        }
        $community = $this->communityRepository->getCommunityByChatId($chatId);
        $question = ArrayHelper::getValue($data, 'message.reply_to_message.text');
        $answer = ArrayHelper::getValue($data, 'message.text');
        if (Str::startsWith($answer, '/qas')) {
            $answer = trim(str_replace('/qas', '', $answer));
            $this->logger->debug('create qa pair on reply', compact('question','answer'));
            $this->manageQuestionService->setUserId($community->owner);
        }

        try {
            $this->manageQuestionService->createFromArray([
                'community_id' => $community->id,
                'question' => [
                    'context' => $question,
                    'is_public' => false,
                    'is_draft' => false,
                    'answer' => [
                        'context' => $answer,
                        'is_draft' => false,
                    ],
                ],
            ]);
        } catch (Throwable $e) {
            $telegramException = new TelegramException($e->getMessage(), $data);
            $telegramException->report();
            return false;
        }
        return true;
    }

    public function detectUserQuestion($data)
    {
        //$this->logger->debug('user custom question handler',$data);
        //todo реализовать если надо вытягивать вопросы из текстового сообщения пользователя
        //  по каким то признакам в самом тексте
        //dd($data);
    }
}