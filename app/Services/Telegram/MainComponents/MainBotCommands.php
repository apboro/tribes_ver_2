<?php

namespace App\Services\Telegram\MainComponents;

use App\Filters\API\QuestionsFilter;
use App\Helper\ArrayHelper;
use App\Helper\PseudoCrypt;
use App\Jobs\SendTeleMessageToChatFromBot;
use App\Models\Community;
use App\Models\Donate;
use App\Models\Knowledge\Question;
use App\Models\Payment;
use App\Models\Tariff;
use App\Models\TariffVariant;
use App\Models\TelegramUser;
use App\Repositories\Community\CommunityRepositoryContract;
use App\Repositories\Payment\PaymentRepositoryContract;
use App\Repositories\Telegram\TelegramConnectionRepositoryContract;
use App\Services\Knowledge\ManageQuestionService;
use App\Services\Telegram;
use App\Services\Telegram\MainBot;
use App\Traits\Declination;
use Askoldex\Teletant\Context;
use Askoldex\Teletant\Addons\Menux;
use Askoldex\Teletant\Entities\Inline\Article;
use Askoldex\Teletant\Entities\Inline\Result;
use Askoldex\Teletant\Entities\Inline\InputTextMessageContent;
use Askoldex\Teletant\Exception\TeletantException;
use App\Repositories\Knowledge\KnowledgeRepositoryContract;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class MainBotCommands
{
    protected MainBot $bot;
    private CommunityRepositoryContract $communityRepo;
    private TelegramConnectionRepositoryContract $connectionRepo;
    private PaymentRepositoryContract $paymentRepo;
    private KnowledgeRepositoryContract $knowledgeRepository;

    protected array $availableBotCommands = [
        //todo здесь список команд, которые нужны боту, и должны быть доступны в реализации
        //  имя команды => описание
        'start' => 'Начало работы с ботом' . "\n",
        'myid' => 'Показывает ваш уникальный ID' . "\n",
        'chatId' => 'Показывает уникальный ID текущего чата' . "\n",
        'tafiff' => 'Список тарифов сообщества',
        'donate' => 'Материальная помощь сообществу',
        'qa' => 'Найти ответ в Базе Знаний сообщества',

    ];
    private ManageQuestionService $manageQuestionService;


    public function __construct(
        TelegramConnectionRepositoryContract $connectionRepo,
        CommunityRepositoryContract          $communityRepo,
        PaymentRepositoryContract            $paymentRepo,
        KnowledgeRepositoryContract          $knowledgeRepository,
        ManageQuestionService                $manageQuestionService
    ) {
        $this->paymentRepo = $paymentRepo;
        $this->connectionRepo = $connectionRepo;
        $this->communityRepo = $communityRepo;
        $this->knowledgeRepository = $knowledgeRepository;
        $this->manageQuestionService = $manageQuestionService;
    }

    public function initBot(MainBot $bot)
    {
        $this->bot = $bot;
    }

    public function initCommand(array $methods = [
        'startBot',
        'startOnGroup',
        'getTelegramUserId',
        'getChatId',
        'getChatType',
        'tariffOnUser',
        'tariffOnChat',
        'inlineCommand',
        "inlineTariffCommand",
        'donateOnChat',
        'donateOnUser',
        'materialAid',
        'personalArea',
        'faq',
        'mySubscriptions',
        'subscriptionSearch',
        'setTariffForUserByPayId',
        'knowledgeSearch',
        'saveForwardMessageInBotChatAsQA',
    ])
    {
        foreach ($methods as $method) {
            if (method_exists($this, $method)) {
                $this->{$method}();
            }
        }
    }

    protected function startBot()
    {
        try {
            $this->createMenu();
            $this->bot->onText('/start {paymentId?}', function (Context $ctx) {
                $ctx->reply('Здравствуйте, вас приветствует TestBot');
                $users = TelegramUser::where('user_id', '!=', NULL)->where('telegram_id', $ctx->getUserID())->get();

                if ($users->first()) {
                    if (str_split($ctx->getChatID(), 1)[0] !== '-') {
                        $ctx->replyHTML('Добро пожаловать в главное меню, ' . $ctx->getUsername() . '! Я бот сервиса по монетизации Telegram-каналов и чатов.' . "\n\n"
                            . 'Ссылка на сайт ' . route('main') . "\n"
                            . 'Создание и настройка проектов происходит в веб кабинете.', Menux::Get('main'));
                    } else $ctx->reply('Здравствуйте, вас приветствует TestBot');
                } else {
                    if (str_split($ctx->getChatID(), 1)[0] !== '-') {
                        $userName = ', ' . $ctx->getUsername() . '!' ?? '';
                        $ctx->replyHTML('Здравствуйте' . $userName, Menux::Get('custom'));
                    }
                }
                if (!empty($ctx->var('paymentId'))) {
                    $this->connectionTariff($ctx);
                }
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function startOnGroup()
    {
        try {
            $this->bot->onCommand('start' . $this->bot->botFullName, function (Context $ctx) {
                $ctx->reply('Здравствуйте, ' . $ctx->getFirstName() . "! \n"
                    . 'Список доступных для вас команд:' . "\n"
                    . $this->getCommandsListAsString());
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function getTelegramUserId()
    {
        try {
            $this->bot->onCommand('myid', function (Context $ctx) {
                if ($ctx->getChatType() != 'channel') {
                    $ctx->reply($ctx->getUserID());
                }
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function getChatId()
    {
        $this->bot->onCommand('chatId', function (Context $ctx) {
            $ctx->reply($ctx->getChatID());
        });
    }

    protected function getChatType()
    {
        $this->bot->onCommand('type', function (Context $ctx) {
            $ctx->reply($ctx->getChatType());
        });
    }

    protected function setCommand()
    {
        try {
            $this->bot->onCommand('setCommand', function (Context $ctx) {
                $commands = [];
                foreach ($this->availableBotCommands as $command => $description) {
                    $commands['command'] = '/' . $command;
                    $commands['description'] = $description;
                }

                $this->bot->getExtentionApi()->setMyCommands(['commands' => $commands]);
                $ctx->reply('Команды зарегистрированы.');
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function tariffOnUser()
    {
        try {
            $this->bot->onCommand('tariff', function (Context $ctx) {
                if (str_split($ctx->getChatID(), 1)[0] !== '-') {
                    $ctx->reply('Доступные тарифы находятся в разделе "Мои подписки".');
                }
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function tariffOnChat()
    {
        try {
            $this->bot->onCommand('tariff' . $this->bot->botFullName, function (Context $ctx) {
                $community = $this->communityRepo->getCommunityByChatId($ctx->getChatID());
                if ($community) {
                    [$text, $menu] = $this->tariffButton($community);
                    $ctx->replyHTML($text, $menu);
                } else $ctx->replyHTML('Тарифов нет.');
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function inlineCommand()
    {
        try {
            $communities = $this->communityRepo->getAllCommunity();
            foreach ($communities as $community) {
                foreach ($community->donate as $donate) {
                    if (!$donate)
                        return false;
                    if (!$donate->inline_link)
                        return false;
                    $this->inlineQuery($donate);
                }
            }
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /**
     * todo реализовать регистрацию всех hash тарифов для инициации inline-команд
     *      !!!Лимит 50 шт на бота, рефакторинг у каждого бота свои сообщества
     * @return false|void
     */
    protected function inlineTariffCommand()
    {
        try {
            $communities = $this->communityRepo->getAllCommunity();
            foreach ($communities as $community) {
                $this->inlineTariffQuery($community->tariff()->first(), $community);
                foreach ($community->tariffVariants as $tv) {
                    if (!$tv)
                        return false;
                    if (!$tv->inline_link)
                        return false;
                    // todo реализовать логику отображения подсказок для инлайн команд тарифов
                    $this->inlineTariffQuery($tv, $community);
                }
            }
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    private function inlineTariffQuery($tariff, $community)
    {
        try {
            $this->bot->onInlineQuery($tariff->inline_link, function (Context $ctx) use ($tariff, $community) {

                $result = new Result();
                $article = new Article(1);
                $message = new InputTextMessageContent();
                $message->parseMode('HTML');

                if ($tariff instanceof TariffVariant) {
                    //todo для одиночного тарифа
                    $menu = Menux::Create('links')->inline();
                    $variant = $tariff;
                    $message->text($variant->title);
                    $price = ($variant->price) ? $variant->price . '₽' : '';
                    $title = ($variant->title) ? $variant->title . ' — ' : '';
                    $period = ($variant->period) ? '/Дней:' . $variant->period : '';
                    $article->description(mb_strimwidth($title, 0, 55, "..."));
                    $menu->row()->uBtn($title . $price . $period, $community->getTariffPaymentLink([
                        'amount' => $variant->price,
                        'currency' => 0,
                        'type' => 'tariff',
                        'telegram_user_id' => null,
                        'inline_link' => $variant->inline_link,
                    ]));
                } elseif ($tariff instanceof Tariff) {
                    //todo для всех активных не персональных тарифов сообщества
                    $image = $tariff->getMainImage() ? $tariff->getMainImage()->url : '';
                    $description = $tariff->publication_description ?? '&#160';
                    $article->description($description);
                    $message->text($description . '<a href="' . route('main') . $image . '">&#160</a>');
                    $article->thumbUrl('' . route('main') . $image);
                    [$text, $menu] = $this->tariffButton($community);
                }
                $article->title($community->title);
                $article->inputMessageContent($message);

                $article->keyboard($menu->getAsObject());
                $result->add($article);
                $ctx->Api()->answerInlineQuery([
                    'inline_query_id' => $ctx->getInlineQueryID(),
                    'results' => (string)$result,
                ]);
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function donateOnChat()
    {
        try {
            $this->bot->onText('/donate-{index?}' . $this->bot->botFullName, function (Context $ctx) {
                $community = $this->communityRepo->getCommunityByChatId($ctx->getChatID());
                $donate = $community->donate()->where('index', $ctx->var('index'))->first();

                if ($community) {
                    $menu = Menux::Create('links')->inline();
                    if ($donate) {
                        foreach ($donate->variants as $variants) {
                            if ($variants->price !== NULL && $variants->isActive !== false) {
                                $key = array_search($variants->currency, Donate::$currency);
                                $currencyLabel = Donate::$currency_labels[$key];
                                $data = [
                                    'amount' => $variants->price,
                                    'currency' => $variants->currency,
                                    'donateId' => $donate->id
                                ];
                                $description = ($variants->description !== NULL) ? ' — ' . $variants->description : '';

                                $menu->row()->uBtn(
                                    $variants->price . $currencyLabel . $description,
                                    $community->getDonatePaymentLink($data)
                                );
                            } elseif ($variants->min_price !== NULL && $variants->max_price !== NULL && $variants->isActive !== false) {
                                $dataNull = [
                                    'amount' => 0,
                                    'currency' => 0,
                                    'donateId' => $donate->id
                                ];
                                $description = ($variants->description !== NULL) ? $variants->description : '';
                                $menu->row()->uBtn($description, $community->getDonatePaymentLink($dataNull));
                            }
                        }
                        $image = ($donate->getMainImage()) ? '<a href="' . route('main') . $donate->getMainImage()->url . '">&#160</a>' : '';
                        $description = ($donate->description !== NULL) ? $donate->description : '';
                        $text = $description . $image;
                        $ctx->replyHTML($text, $menu);
                    } else $ctx->reply('В сообществе не определен донат с указанным индексом');
                } else $ctx->reply('Сообщество не подключено.');
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function donateOnUser()
    {
        try {
            $this->bot->onCommand('donate', function (Context $ctx) {
                if (str_split($ctx->getChatID(), 1)[0] !== '-') {

                    $communities = $this->communityRepo->getCommunitiesForMemberByTeleUserId($ctx->getChatID());

                    if ($communities->first()) {
                        $menu = Menux::Create('links')->inline();
                        foreach ($communities as $community) {
                            $menu->row()->btn($community->title, 'variant:' . $community->id);
                        }
                        $ctx->reply('Выберите сообщество, которому желаете оказать материальную помощ.', $menu);
                        $ctx->enter('donate');
                    } else
                        $ctx->reply('Вы не состоите в сообществе.');
                }
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function subscriptionSearch()
    {
        try {
            $this->bot->onHears('🔍Найти подписку', function (Context $ctx) {
                $ctx->reply('Пожалуйста введите идентификатор платежа. Пример: payment-1111');
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function setTariffForUserByPayId()
    {
        try {
            $this->bot->onHears('payment-{id:string}', function (Context $ctx) {
                $payment = Payment::where('paymentId', $ctx->var('id'))->where('activated', false)->first();
                if ($payment) {
                    $payment->telegram_user_id = $ctx->getUserID();
                    $payment->save();
                } else {
                    $ctx->reply('Ваша подписка уже активирована, что-бы получить ссылку на ресурс пройдите в раздел "Мои подписки".');
                }

                if ($payment && $payment->type == 'tariff' && $payment->status == 'CONFIRMED') {

                    $community = $payment->community;

                    $ty = TelegramUser::where('telegram_id', $ctx->getUserID())->first();

                    if (!$ty->communities()->find($community->id)) {
                        $ty->communities()->attach($community, [
                            'role' => 'member',
                            'accession_date' => time()
                        ]);
                        $this->bot->getExtentionApi()->unKickUser($ctx->getUserID(), $community->connection->chat_id);
                    }

                    $variant = $community->tariff->variants()->find($payment->payable_id);

                    if (!$ty->tariffVariant->find($variant->id)) {
                        foreach ($ty->tariffVariant->where('tariff_id', $community->tariff->id) as $userTariff) {
                            if ($userTariff->id !== $variant->id)
                                $ty->tariffVariant()->detach($userTariff->id);
                        }
                        $ty->tariffVariant()->attach($variant, ['days' => $variant->period, 'prompt_time' => date('H:i')]);
                    } else {

                        $ty->tariffVariant()->updateExistingPivot($variant->id, [
                            'days' => $variant->period,
                            'prompt_time' => date('H:i'),
                            'isAutoPay' => true
                        ]);
                    }

                    $payment->activated = true;
                    $payment->save();
                    $menu = Menux::Create('links')->inline();
                    $menu->row()->btn('Получить пригласительную ссылку на ресурс', 'access-' . $community->connection->id);
                    $ctx->reply('Подписка найдена', $menu);
                    $this->access();
                }
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function materialAid()
    {
        try {
            $this->bot->onHears('❗Оказать материальную помощь', function (Context $ctx) {

                $communities = $this->communityRepo->getCommunitiesForMemberByTeleUserId($ctx->getChatID());

                if ($communities->first()) {
                    $menu = Menux::Create('links')->inline();

                    foreach ($communities as $community) {
                        $menu->row()->btn($community->title, 'variant:' . $community->id);
                    }

                    $ctx->reply('Выберите сообщество, которому желаете оказать материальную помощ.', $menu);
                    $ctx->enter('donate');
                } else $ctx->reply('Выбранное сообщество не принимает донаты.');
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function personalArea()
    {
        try {
            $this->bot->onHears('🚀Личный кабинет', function (Context $ctx) {
                $menu = Menux::Create('links')->inline();
                $menu->row()->uBtn('Перейти в личный кабинет', route('main'));
                $ctx->reply('Для того чтобы перейти в личный кабинет перейдите по ссылке', $menu);
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function faq()
    {
        try {
            $this->bot->onHears('🔧Помощь', function (Context $ctx) {
                $menu = Menux::Create('links')->inline();
                $menu->row()->uBtn('Помощь', route('faq.index'));
                $ctx->reply('Для того чтобы получить помощь перейдите по ссылке', $menu);
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function mySubscriptions()
    {
        try {
            $this->bot->onHears('📂Мои подписки', function (Context $ctx) {
                $menu = Menux::Create('links')->inline();
                $communities = $this->communityRepo->getCommunitiesForMemberByTeleUserId($ctx->getChatID());
                if ($communities->first()) {
                    foreach ($communities as $community) {
                        $menu->row()->btn($community->title, 'subscription-' . $community->connection_id);
                    }
                    $ctx->reply('Выберите подписку', $menu);
                } else $ctx->reply('У вас нет подписок');
            });
            $this->subscription();
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function knowledgeSearch()
    {

        try {
            $this->bot->onText('/qa {search?}', function (Context $ctx) {

                $message = $ctx->update()->message();
                $this->bot->logger()->debug('Поиск по БЗ');
                $searchText = $ctx->var('search');
                $replyToUser = $message->from()->username() ?? $message->from()->firstName();

                if (!$message->replyToMessage()->isEmpty()) {
                    $reply = $message->replyToMessage();
                    if (empty($searchText)) {
                        $searchText = $reply->text();
                    }
                    $replyToUser = $reply->from()->username() ?? $reply->from()->firstName();
                    //$reply->messageId()
                }
                $searchText = trim($searchText);
                Log::debug(" search.$searchText");
                if (empty($searchText) || strlen($searchText) <= 3) {
                    $ctx->replyHTML("@$replyToUser Слишком короткий поисковый запрос.");
                    return;
                }
                $community = $this->communityRepo->getCommunityByChatId($ctx->getChatID());
                if (!$community) {
                    $ctx->replyHTML("@$replyToUser Сообщество не подключено.");
                    return;
                }

                $filters = new QuestionsFilter(new Request());
                $filters->replace([
                    'published' => 'public',
                    'draft' => 'not_draft',
                    'per_page' => 3,
                    'page' => 1,
                    'full_text' => $searchText,
                ]);

                $paginateQuestionsCollection = $this->knowledgeRepository->getQuestionsByCommunityId($community->id, $filters);
                if ($paginateQuestionsCollection->isEmpty()) {
                    $ctx->replyHTML("@$replyToUser Ответов не найдено.");
                    return;
                }
                $context = "Для @$replyToUser из Базы Знаний \n";
                $context .= "<b>--------------------------</b> \n";
                $context .= $this->prepareQuestionsList($paginateQuestionsCollection);
                if ($paginateQuestionsCollection->total() > $paginateQuestionsCollection->perPage()) {
                    $context .= '<a href="' . $community->getPublicKnowledgeLink() . '?search_text=' . $searchText . '">' .
                        "Смотреть остальные вопросы - ответы" .
                        "</a>" . " \n";
                }
                $ctx->replyHTML($context);
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    //-------------------------------

    private function prepareQuestionsList(LengthAwarePaginator $paginateQuestionsCollection): string
    {
        try {
            $context = '';
            $this->bot->logger()->debug('Список вопросов в хтмл для реплики бота');
            /** @var Question $question */
            foreach ($paginateQuestionsCollection as $question) {
                //todo написать список ответов со ссылкой на каждый ответ и ссылкой на веб версию БЗ
                $context .= '<a href="' . $question->getPublicLink() . '">' .
                    Str::limit(strip_tags($question->context), 60, "...") .
                    "</a>" . " \n" .
                    '<span class="tg-spoiler">' . Str::limit(strip_tags($question->answer->context ?? "Нет ответа"), 120, "...") . '</span>' .
                    " \n";
                $context .= '<b>--------------------------</b>' . " \n";
            }

            return $context;
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    private function subscription()
    {
        try {
            $this->bot->onAction('subscription-{id:string}', function (Context $ctx) {
                $connectionId = $ctx->var('id');
                $menu = Menux::Create('links')->inline();
                $connection = $this->connectionRepo->getConnectionById($connectionId);

                $user = TelegramUser::where('telegram_id', $ctx->getUserID())->with('tariffVariant')->first();
                $tariffVariant = $connection->community->tariff->variants()->whereHas('payFollowers', function ($q) use ($user) {
                    $q->where('id', $user->id);
                })->first();

                if ($tariffVariant) {
                    $menu->row()->btn('Получить доступ к ресурсу', 'access-' . $connectionId)
                        ->row()->btn('Продлить подписку', 'extend-' . $connectionId)
                        ->row()->btn('Отписаться', 'unsubscribe-' . $connectionId);

                    $status = ($tariffVariant->payFollowers()->where('id', $user->id)->first()->pivot->days > 0) ? 'Активный' : 'Неактивный';
                    $tariffTitle = ($tariffVariant) ? $tariffVariant->title : 'Пробный период';
                    $period = 0;

                    foreach ($user->tariffVariant->where('tariff_id', $connection->community->tariff->id) as $userTariff) {
                        $period += $userTariff->pivot->days;
                    }
                    $periodDays = ($period !== 0) ? "\nОсталось дней: " . $period : "\nСрок действия оплаченного тарифа закончился";
                    $ctx->reply(
                        "Канал: $connection->chat_title 
                        \nСтатус: $status 
                        \nТариф: $tariffTitle
                        $periodDays",
                        $menu
                    );
                } else {
                    $ctx->reply("Подписка отсуствует.");
                }
            });
            $this->access();
            $this->extend();
            $this->unsubscribe();
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /**
     * сохранение пары вопрос ответ из кеша "author_chat_bot_111_forward_message-multi"['q','a']
     * @return void
     */
    private function saveForwardMessageInBotChatAsQA()
    {
        try {
            $this->bot->onAction('add-qa-community-{id:string}', function (Context $ctx) {

                $communityId = $ctx->var('id');
                $mChatId = $ctx->callbackQuery()->from()->id() ?? null;
                if (empty($mChatId)) {
                    $this->bot->logger()
                        ->debug(
                            'saveForwardMessageInBotChatAsQA: Не определился личный чат автора',
                            $ctx->callbackQuery()->export()
                        );
                    return;
                }
                $communities = $this->communityRepo->getCommunitiesForOwnerByTeleUserId($mChatId)->keyBy('id');

                if (!$communities->has($communityId)) {
                    $this->bot->logger()
                        ->debug(
                            'saveForwardMessageInBotChatAsQA: Не найдено сообщество или оно не принадлежит автору',
                            $ctx->callbackQuery()->export()
                        );
                    return;
                }
                $community = $communities->get($communityId);

                $key = "author_chat_bot_{$mChatId}_forward_message-multi";
                $data = Cache::get($key, null);
                if (empty($data)) {
                    $this->bot->logger()->debug('saveForwardMessageInBotChatAsQA: Нет данных в кеше', [
                        'key' => $key
                    ]);
                    return;
                }
                $this->bot->logger()
                    ->debug(
                        'saveForwardMessageInBotChatAsQA: запись вопрос ответ для сообщества',
                        array_merge(['community_id' => $community->id], $data)
                    );

                $this->manageQuestionService->setUserId($community->owner);
                $this->manageQuestionService->createFromArray([
                    'community_id' => $community->id,
                    'question' => [
                        'context' => ArrayHelper::getValue($data, 'q'),
                        'is_public' => false,
                        'is_draft' => false,
                        'answer' => [
                            'context' => ArrayHelper::getValue($data, 'a'),
                            'is_draft' => false,
                        ],
                    ],
                ]);
                $ctx->reply("Вопрос ответ сохранен в сообщество: {$community->title}");
                Cache::forget($key);
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    private function unsubscribe()
    {
        try {
            $this->bot->onAction('unsubscribe-{id:string}', function (Context $ctx) {
                $connectionId = $ctx->var('id');
                $connection = $this->connectionRepo->getConnectionById($connectionId);
                $ty = TelegramUser::where('telegram_id', $ctx->getUserID())->with('tariffVariant')->first();
                $tariffVariant = $connection->community->tariff->variants()->whereHas('payFollowers', function ($q) use ($ty) {
                    $q->where('id', $ty->id);
                })->first();
                $community = $tariffVariant->tariff->community;
                if ($ty->tariffVariant->find($tariffVariant->id)) {
                    $ty->tariffVariant()->updateExistingPivot($tariffVariant->id, [
                        'isAutoPay' => false
                    ]);
                    if ($ty->communities()->find($community->id)->pivot->role !== 'administrator') {
                        if ($connection->telegram_user_id == $ctx->getUserID()) {
                            // $this->bot->getExtentionApi()->kickUser($ty->telegram_id, $connection->chat_id);
                            $ty->communities()->updateExistingPivot($community->id, [
                                'exit_date' => time()
                            ]);
                        }
                        $ctx->reply('Подписка отменена.');
                    } else {
                        $ctx->reply('Подписка отменена.');
                    }
                }
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    private function access()
    {
        try {
            $this->bot->onAction('access-{id:string}', function (Context $ctx) {
                $connectionId = $ctx->var('id');
                $connection = $this->connectionRepo->getConnectionById($connectionId);

                $invite = $this->createAndSaveInviteLink($connection);
                $ctx->replyHTML('Ссылка: <a href="' . $invite . '">' . $connection->chat_title . '</a>');
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    private function extend()
    {
        try {
            $this->bot->onAction('extend-{id:string}', function (Context $ctx) {
                $connectionId = $ctx->var('id');
                $community = $this->connectionRepo->getConnectionById($connectionId)->community;
                if ($community) {
                    if ($community->tariff) {
                        $menu = Menux::Create('links')->inline();
                        $text = 'Доступные тарифы';
                        if ($community->tariff->variants->first()) {
                            foreach ($community->tariff->variants as $variant) {
                                if ($variant->price !== 0 && $variant->isActive == true) {
                                    $price = ($variant->price) ? $variant->price . '₽' : '';
                                    $title = ($variant->title) ? $variant->title . ' — ' : '';
                                    $period = ($variant->period) ? '/Дней:' . $variant->period : '';
                                    $menu->row()->uBtn($title . $price . $period, $community->getTariffPaymentLink([
                                        'amount' => $variant->price,
                                        'currency' => 0,
                                        'type' => 'tariff',
                                        'telegram_user_id' => $ctx->getUserID()
                                    ]));
                                }
                            }
                            $ctx->replyHTML($text, $menu);
                        } else ($ctx->reply('Тарифы не установлены для сообщества'));
                    }
                } else ($ctx->reply('Сообщество подключено неправильно'));
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    private function connectionTariff(Context $ctx)
    {
        try {
            Telegram::paymentUser(
                $ctx->getUserID(),
                $ctx->getUsername(),
                $ctx->getFirstName(),
                $ctx->getLastName(),
                $ctx->var('paymentId'),
                $this->bot->getExtentionApi()
            );

            $trial = strpos($ctx->var('paymentId'), 'trial');
            $payId = PseudoCrypt::unhash($ctx->var('paymentId'));
            $payment = $this->paymentRepo->getPaymentById($payId);
            if ($trial === false) {
                if ($payment && $payment->type == 'tariff') {
                    $link = $this->createAndSaveInviteLink($payment->community->connection);
                    $invite = ($link)
                        ? "\n" . 'Чтобы вступить в сообщество, нажмите сюда: <a href="' . $link . '">Подписаться</a>' : '';

                    $message = $payment->community->tariff->thanks_description ?? '';

                    $image = ($payment->community->tariff->getThanksImage()) ? ' <a href="' . route('main') . $payment->community->tariff->getThanksImage()->url . '">&#160</a>' : '';
                    $variant = $payment->community->tariff->variants()->find($payment->payable_id);
                    if ($variant->isActive === true) {
                        $variantName = $variant->title ?? '{Название тарифа}';
                        $date = date('d.m.Y H:i', strtotime("+$variant->period days")) ?? 'Неизвестно';
                    }

                    $defMassage = "\n\n" . 'Выбранный тариф: ' . $variantName . "\n" . 'Cрок окончания действия: ' . $date . "\n";
                    $ctx->replyHTML($image . $message . $defMassage . $invite);
                    //todo отправить сообщение автору через личный чат с ботом,
                    $ty = TelegramUser::where([
                        'telegram_id' => $ctx->getUserID()
                    ])->first();

                    $payerName = $ty->publicName() ?? '';
                    $tariffName = $variant->title ?? '';
                    $tariffCost = ($payment->amount / 100) ?? 0;
                    $tariffEndDate = Carbon::now()->addDays($variant->period)->format('d.m.Y') ?? '';
                    $message = "Участник $payerName оплатил $tariffName в сообществе {$payment->community->title},
                                стоимость $tariffCost рублей действует до $tariffEndDate г.";
                    Log::info('send tariff pay message to own author chat bot', [
                        'message' =>  $message
                    ]);
                    $authorTeleUserId = $payment->community->connection->telegram_user_id ?? 0;
                    SendTeleMessageToChatFromBot::dispatch(config('telegram_bot.bot.botName'), $authorTeleUserId, $message);
                }
            } else {
                $communityId = str_replace('trial', '', $ctx->var('paymentId'));
                $community = $this->communityRepo->getCommunityById($communityId);
                if ($community) {
                    $link = $this->createAndSaveInviteLink($community->connection);
                    $invite = ($link) ? "\n" . 'чтобы вступить в сообщество, нажмите сюда: <a href="' . $link . '">Подписаться</a>' : '';

                    $message = $community->tariff->thanks_description ?? '';

                    $image = ($community->tariff->getThanksImage()) ? ' <a href="' . route('main') . $community->tariff->getThanksImage()->url . '">&#160</a>' : '';
                    foreach ($community->tariff->variants as $variant) {
                        if ($variant->price == 0 && $variant->isActive == true) {
                            $variantName = $variant->title ?? 'Пробный период';
                            $date = date('d.m.Y H:i', strtotime("+$variant->period days")) ?? 'Неизвестно';
                        }
                    }
                    $defMassage = "\n\n" . 'Выбранный тариф: ' . $variantName . "\n" . 'Cрок окончания действия: ' . $date . "\n";

                    $ctx->replyHTML($image . $message . $defMassage . $invite);
                } else $ctx->replyHTML('Сообщество не существует');
            }
        } catch (\Exception $e) {
            return $ctx->reply('Что-то пошло не так, пожалуйста обратитесь в службу поддержки.' . 'Ошибка:'
                . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    private function createAndSaveInviteLink($telegramConnection)
    {
        try {
            $invite = $this->bot->getExtentionApi()->createInviteLink($telegramConnection->chat_id);
            $telegramConnection->update([
                'chat_invite_link' => $invite
            ]);
            return $invite;
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    private function createMenu()
    {
        try {
            Menux::Create('menu', 'main')
                ->row()->btn('🚀Личный кабинет')
                ->row()->btn('📂Мои подписки');

            Menux::Create('menuCustom', 'custom')
                ->row()->btn('🚀Личный кабинет')
                ->row()->btn('📂Мои подписки');
        } catch (\Exception $e) {
        }
    }

    private function tariffButton($community, $userId = NULL)
    {
        try {
            $menu = Menux::Create('links')->inline();
            $text = 'Доступные тарифы';
            $variants = $community->tariff->variants()
                ->where('isActive', true)
                ->where('isPersonal', false)
                ->get();
            if ($variants->count() == 0) {
                return ['Тарифы не установлены для сообщества', ''];
            }
            foreach ($variants as $variant) {
                $price = ($variant->price) ? $variant->price . '₽' : '';
                $title = ($variant->title) ? $variant->title . ' — ' : '';
                $period = ($variant->period) ? '/Дней:' . $variant->period : '';
                $menu->row()->uBtn($title . $price . $period, $community->getTariffPaymentLink([
                    'amount' => $variant->price,
                    'currency' => 0,
                    'type' => 'tariff',
                    'telegram_user_id' => $userId
                ]));
            }
            return [$text, $menu];
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    private function inlineQuery($donate)
    {
        try {
            $this->bot->onInlineQuery($donate->inline_link, function (Context $ctx) use ($donate) {

                $result = new Result();
                $article = new Article(1);
                $message = new InputTextMessageContent();

                $image = $donate->getMainImage() ? $donate->getMainImage()->url : '';
                $description = $donate->description ? $donate->description : '';
                $message->text($description . '<a href="' . route('main') . $image . '">&#160</a>');

                $message->parseMode('HTML');
                $article->title($donate->community->title);

                if ($donate->description)
                    $article->description(mb_strimwidth($donate->description, 0, 55, "..."));

                $article->inputMessageContent($message);
                $article->thumbUrl('' . route('main') . $image);

                $menu = Menux::Create('a')->inline();
                foreach ($donate->variants as $variant) {
                    if ($variant->price && $variant->isActive !== false) {
                        $key = array_search($variant->currency, Donate::$currency);

                        $currencyLabel = Donate::$currency_labels[$key];
                        $data = [
                            'amount' => $variant->price,
                            'currency' => $variant->currency,
                            'donateId' => $donate->id
                        ];

                        if ($variant->description) {
                            $menu->row()->uBtn(
                                $variant->price . $currencyLabel . ' — ' . $variant->description,
                                $donate->community->getDonatePaymentLink($data)
                            );
                        } else {
                            $menu->row()->uBtn($variant->price . $currencyLabel, $donate->community->getDonatePaymentLink($data));
                        }
                    } elseif ($variant->min_price && $variant->max_price && $variant->isActive !== false) {
                        $dataNull = [
                            'amount' => 0,
                            'currency' => 0,
                            'donateId' => $donate->id
                        ];
                        $variantDesc = $variant->description ? $variant->description : 'Произвольная сумма';
                        $menu->row()->uBtn($variantDesc, $donate->community->getDonatePaymentLink($dataNull));
                    }
                }

                $article->keyboard($menu->getAsObject());
                $result->add($article);
                $ctx->Api()->answerInlineQuery([
                    'inline_query_id' => $ctx->getInlineQueryID(),
                    'results' => (string)$result,
                ]);
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Отправляет сообщение в группу с донатами
     * @param int $chatId
     * @param int $donateId
     */
    public function sendDonateMessage(int $chatId, int $donateId)
    {
        try {
            $donate = Donate::find($donateId);
            if ($donate) {
                foreach ($donate->variants as $variant) {
                    if ($variant->price && $variant->isActive !== false) {
                        $key = array_search($variant->currency, Donate::$currency);
                        $currencyLabel = Donate::$currency_labels[$key];
                        $data = [
                            'amount' => $variant->price,
                            'currency' => $variant->currency,
                            'donateId' => $donate->id
                        ];
                        $description = ($variant->description) ? ' — ' . $variant->description : '';
                        $sumDonate[] = [[
                            'text' => $variant->price . $currencyLabel . $description,
                            "url" => $donate->community->getDonatePaymentLink($data)
                        ]];
                    } elseif ($variant->min_price && $variant->max_price && $variant->isActive !== false) {
                        $dataNull = [
                            'amount' => 0,
                            'currency' => 0,
                            'donateId' => $donate->id
                        ];
                        $description = ($variant->description) ? $variant->description : 'Произвольная сумма';
                        $sumDonate[] = [[
                            'text' => $description,
                            "url" => $donate->community->getDonatePaymentLink($dataNull)
                        ]];
                    }
                }
            }
            $desc = $donate->description ?? '';
            $image = $donate->getMainImage() ? '<a href="' . route('main') . $donate->getMainImage()->url . '">&#160</a>' : '';
            $text = $desc . $image;
            isset($sumDonate) ? $this->bot->getExtentionApi()->sendMess($chatId, $text, false, $sumDonate) : NULL;
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Отправляет сообщение в группу с тарифами
     * @param Community $community
     */
    public function sendTariffMessage(Community $community)
    {
        try {
            $tariff = $community->tariff;
            foreach ($tariff->variants as $variant) {
                if ($variant->price !== 0 && $variant->isActive !== false && $variant->isPersonal == false) {
                    $data = [
                        'amount' => $variant->price,
                        'currency' => 0,
                        'type' => 'tariff',
                        'telegram_user_id' => NULL
                    ];

                    $button[] = [[
                        'text' => $variant->title . ' — ' . $variant->price . '₽' . '/' . $variant->period . ' ' . Declination::defineDeclination($variant->period),
                        "url" => $community->getTariffPaymentLink($data)
                    ]];
                }
            }

            $message = $tariff->publication_description ?? '';
            $image = ($tariff->getPublicationImage()) ? '<a href="' . route('main') . $tariff->getPublicationImage()->url . '">&#160</a>' : '';
            $text = $message . $image;

            $chatId = $community->connection->chat_id ?? '';
            if (count($button)) {
                $this->bot->getExtentionApi()->sendMess($chatId, $text, false, $button);
            }
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /**Отправляет сообщение пользователю с тарифами
     * @param string $botName
     * @param int $chatId
     * @param string $textMessage
     * @param $community
     * @param bool $preview
     * @param array $keyboard
     */
    public function sendMessageFromBotWithTariff(int $chatId, string $textMessage, Community $community)
    {
        try {
            $tariff = $community->tariff;
            foreach ($tariff->variants as $variant) {
                if ($variant->price !== 0 && $variant->isActive !== false && $variant->isPersonal == false) {
                    $data = [
                        'amount' => $variant->price,
                        'currency' => 0,
                        'type' => 'tariff',
                        'telegram_user_id' => NULL
                    ];

                    $button[] = [[
                        'text' => $variant->title . ' — ' . $variant->price . '₽' . '/' . $variant->period . ' ' . Declination::defineDeclination($variant->period),
                        "url" => $community->getTariffPaymentLink($data)
                    ]];
                }
            }

            $text = $textMessage ?? '';

            if (count($button)) {
                $this->bot->getExtentionApi()->sendMess($chatId, $text, false, $button);
            }
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    private function getCommandsListAsString(): string
    {
        $text = '';
        foreach ($this->availableBotCommands as $command => $description) {
            $text .= $command . ' - ' . $description . "\n";
        }
        return $text;
    }
}
