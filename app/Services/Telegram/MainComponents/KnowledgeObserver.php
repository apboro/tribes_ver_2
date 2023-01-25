<?php

namespace App\Services\Telegram\MainComponents;

use App\Exceptions\TelegramException;
use App\Helper\ArrayHelper;
use App\Models\Knowledge\Category;
use App\Repositories\Community\CommunityRepositoryContract;
use App\Services\Knowledge\ManageQuestionService;
use App\Services\TelegramMainBotService;
use Askoldex\Teletant\Addons\Menux;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Throwable;

class KnowledgeObserver
{
    private CommunityRepositoryContract $communityRepository;
    private ManageQuestionService $manageQuestionService;
    private Logger $logger;
    private TelegramMainBotService $mainBotService;

    public function __construct(
        CommunityRepositoryContract $communityRepository,
        ManageQuestionService       $manageQuestionService,
        Logger                      $logger,
        TelegramMainBotService $mainBotService
    )
    {
        $this->communityRepository = $communityRepository;
        $this->manageQuestionService = $manageQuestionService;
        $this->logger = $logger;
        $this->mainBotService = $mainBotService;
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

        try {
            if (Str::startsWith($answer, '/qas')) {
                $answer = trim(str_replace('/qas', '', $answer));
                $this->logger->debug('create qa pair on reply', compact('question', 'answer'));
                $this->manageQuestionService->setUserId($community->owner);
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
            }

        } catch (Throwable $e) {
            $telegramException = new TelegramException($e->getMessage(), $data);
            $telegramException->report();
            return false;
        }
        return true;
    }

    public function detectUserQuestion($data)
    {
        $this->logger->debug('user custom question handler', $data);
        //todo реализовать если надо вытягивать вопросы из текстового сообщения пользователя
        //  по каким то признакам в самом тексте
        //dd($data);
    }

    /**
     * обработка пары вопрос ответ при пересылке боту двух сообщений
     * 'message.chat.id' == TeleUserId
     * @param $data
     * @return void
     */
    public function detectForwardMessageBotQuestion($data, $params = [])
    {
        $this->logger->debug('detect_forward_message_bot_question', $data);
        //todo первое бот событие пересланного сообщения устанавливает флаг записи
        // второе аналогичное бот событие записывает вопрос и ответ
        $mChatId = ArrayHelper::getValue($data, 'message.chat.id');
        $key = "author_chat_bot_{$mChatId}_forward_message";
        $text = ArrayHelper::getValue($data, 'message.text');
        if (empty(trim($text))) {
            //пустой не записываем
            return;
        }
        if (empty($params['botName']) || !$this->mainBotService->hasBotByName($params['botName'])) {
            $this->logger->error('empty botName param or mainBotService has not instance that bot', $data);
            return;
        }

        $firstMessageAsQuestion = Cache::get($key, null);
        if ($firstMessageAsQuestion) {
            $this->logger->debug('create qa pair on forward messages');
            $communityCollection = $this->communityRepository->getCommunitiesForOwnerByTeleUserId($mChatId);
            if ($communityCollection->count() == 1) {
                $community = $communityCollection->first();
                $this->manageQuestionService->setUserId($community->owner);
                $this->manageQuestionService->createFromArray([
                    'community_id' => $community->id,
                    'question' => [
                        'context' => $firstMessageAsQuestion,
                        'is_public' => false,
                        'is_draft' => false,
                        'answer' => [
                            'context' => $text,
                            'is_draft' => false,
                        ],
                    ],
                ]);
            } elseif($communityCollection->count() > 1) {
                //todo придумать реализцию сценария с уточнением к какому сообществу относится пара вопрос ответ
                // учитывать временной лаг 5 сек для автора что бы успел ввести
                Cache::add($key.'-multi', [
                    'q' => $firstMessageAsQuestion,
                    'a' => $text,
                ], \DateInterval::createFromDateString('10 minutes'));
                $menu = [];
                foreach ($communityCollection as $eachCommunity) {
                    $menu[][] = ['text' => $eachCommunity->title, 'callback_data' => 'add-qa-community-' . $eachCommunity->id];
                }
                $this->mainBotService->sendMessageFromBot($params['botName'], $mChatId, 'Выбирете сообщество',false, $menu);
                $this->logger->debug('telegram scene on forward messages for more communities');
            }
            Cache::forget($key);
        } else {
            // кеш ставится на 5 сек , как максимальное время ожидания второго бот события
            // для получения ответа на вопрос
            Cache::add($key, $text, \DateInterval::createFromDateString('5 seconds'));
        }
        //dd($data);
    }
}