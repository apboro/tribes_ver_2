<?php

namespace App\Services\Telegram\MainComponents;

use App\Filters\API\QuestionsFilter;
use App\Helper\ArrayHelper;
use App\Helper\PseudoCrypt;
use App\Jobs\SendEmails;
use App\Jobs\SendTeleMessageToChatFromBot;
use App\Jobs\Telegram\InitCommunityConnectionJob;
use App\Logging\TelegramBotActionHandler;
use App\Models\Author;
use App\Models\Community;
use App\Models\Donate;
use App\Models\Knowledge\Category;
use App\Models\Knowledge\Question;
use App\Models\Payment;
use App\Models\Tariff;
use App\Models\TariffVariant;
use App\Models\TelegramChatTheme;
use App\Models\TelegramUser;
use App\Models\TelegramUserList;
use App\Models\TelegramUserReputation;
use App\Models\TelegramUserTariffVariant;
use App\Repositories\Community\CommunityRepositoryContract;
use App\Repositories\Payment\PaymentRepositoryContract;
use App\Repositories\Telegram\TelegramConnectionRepositoryContract;
use App\Services\Knowledge\ManageQuestionService;
use App\Services\Telegram;
use App\Services\Telegram\MainBot;
use App\Services\TelegramLogService;
use App\Traits\Declination;
use Askoldex\Teletant\Addons\Keyboard;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MainBotCommands
{
    private const CABINET = 'Личный кабинет 🚀';
    private const CABINET_COMMAND = 'getspodial'; //🚀
    private const SUPPORT = 'Поддержка 🚀'; //
    private const SUPPORT_MESSAGE = '/issue'; //🚀
    private const CONNECT_CHAT_TO_SPODIAL = 'Подключить чат к Spodial'; //🚀

    private const KNOWLEDGE_BASE = 'База знаний 🚀';
    private const KNOWLEDGE_BASE_BOT = 'database';
    private const MY_SUBSRUPTION = 'Мои чаты 🚀';
    private const SUPPORT_BOT = 'support';

    private const REPUTATION = 'Репутация'; //🚀
    private const ADD_NEW_CHAT_TEXT = 'Добавить чат 🚀';
    private const ADD_NEW_CHAT_COMMAND = 'new_chat';

    private const BOT_INVITE_TO_GROUP_SETTINGS = 'startgroup&admin=promote_members+delete_messages+restrict_members+invite_users+pin_messages+manage_video_chats';

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
        'chatid' => 'Показывает уникальный ID текущего чата' . "\n",
        'tariff' => 'Список тарифов сообщества',
        'donate' => 'Материальная помощь сообществу',
        'qa' => 'Найти ответ в Базе Знаний сообщества',
        'help' => 'help',
//        self::KNOWLEDGE_BASE_BOT => 'База знаний',
//        self::SUPPORT_BOT => 'Поддержка',
    ];
    private ManageQuestionService $manageQuestionService;


    public function __construct(
        TelegramConnectionRepositoryContract $connectionRepo,
        CommunityRepositoryContract          $communityRepo,
        PaymentRepositoryContract            $paymentRepo,
        KnowledgeRepositoryContract          $knowledgeRepository,
        ManageQuestionService                $manageQuestionService
    )
    {
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
        'onStartDonate',
        'startOnGroup',
        'getTelegramUserId',
        'getChatId',
        'getChatType',
        'tariffOnUser',
        'tariffOnChat',
        'inlineCommand',
        'inlineTariffQuery',
        'inlineShop',
        'donateOnChat',
        'helpOnChat',
        'helpOnBot',
        'donateOnUser',
        'materialAid',
        'personalArea',
        'faq',
        'mySubscriptions',
        'subscriptionSearch',
        'setTariffForUserByPayId',
        'knowledgeSearch',
        'saveForwardMessageInBotChatAsQA',
        'support',
        'getSpodial',
        'reputation',
        'getDonateData',
        'addNewGroup',
        'findThemes',
    ])
    {
        foreach ($methods as $method) {
            if (method_exists($this, $method)) {
                $this->{$method}();
            }
        }
    }

    /**
     * @param Context $ctx
     * @return bool
     */
    private function isPrivateBot(Context $ctx): bool
    {
        return $ctx->getChatType() === 'private';
    }

    /**
     * @param Context $ctx
     *
     * @return bool
     */
    private function isPrivateMessageToBot(Context $ctx): bool
    {
        return $ctx->getFrom()->id() === $ctx->getChatID();
    }

    /**
     *  /start
     * @return void
     */
    protected function startBot()
    {
        log::info('bot start command');
        try {
            $this->createMenu();
            $start = function (Context $ctx) {
                log::info('/start {paymentId?} ____________');
                $messageUserOwner = 'Вы успешно запустили бота Spodial! ' . "\n\n"
                    . 'Моя задача помогать комьюнити-менеджерам в управлении чатами. Мой основной функционал настраивается' . "\n"
                    . 'в ЛК на платформе ' . route('main') . ', в диалоге я могу по вашему запросу вам помочь:' . "\n\n"
                    . ' • Получить ссылку на личный кабинет и базу знаний' . "\n"
                    . ' • Обратиться в службу поддержки' . "\n"
                    . ' • Подключить новый чат к Spodial' . "\n"
                    . ' • Получить информацию по ТОП-10 участникам вашего чата.' . "\n"
                    . 'Также я могу выполнять команды /ban, /kick, /mute. ' . "\n"
                    . 'Используйте встроенную клавиатуру ниже, чтобы начать.';

                $messageForMember = 'Вы успешно запустили бота Spodial!' . "\n\n"
                    . 'Моя задача помогать комьюнити-менеджерам в управлении чатами. Мой основной функционал настраивается' . "\n"
                    . 'в ЛК на платформе spodial.com, в диалоге я могу по вашему запросу вам помочь:' . "\n"
                    . ' • Получить ссылку на личный кабинет и базу знаний' . "\n"
                    . ' • Обратиться в службу поддержки' . "\n"
                    . ' • Подключить новый чат к Spodial' . "\n"
                    . 'Используйте встроенную клавиатуру ниже, чтобы начать.';

                // in private to bot
                $custoMenu = Menux::Get('main');
                $custoMenu->default();

                if ($this->isPrivateMessageToBot($ctx)) {
                    if (TelegramUser::where('telegram_id', $ctx->getUserID())->firstOrNew()->connections()->first()) {
//                    $ctx->ansInlineQuery()
                        $ctx->replyHTML($messageUserOwner, Menux::Get('owner'));
                    } else {
                        $ctx->replyHTML($messageForMember, Menux::Get('custom'));
                    }
                }

                $this->save_log(
                    TelegramBotActionHandler::START_BOT,
                    TelegramBotActionHandler::ACTION_SEND_HELLO_MESSAGE,
                    $ctx);
            };
            //handle start with payment
            /*$this->bot->onText('/start payment-{paymentId:string}', function (Context $ctx) {
                $this->connectionTariff($ctx);
            });*/

            //handle start with donate
            $this->bot->onText('/start donate-{donate_hash:string}_{amount:integer}', function (Context $ctx) {
                $this->donateButtons($ctx);
            });

            $this->bot->onText('/start tariff-{tariff_hash:string}_{variant_hash:string}', function (Context $ctx) {
                $this->tariffBuyButton($ctx);
            });

            $this->bot->onText('/start shop-{author_hash:string}_{role:string}', function (Context $ctx) {
                $this->shopButton($ctx);
            });

//          $this->bot->onText('/start {paymentId?}', $start);
            $this->bot->onCommand('start', $start);
        } catch (\Exception $e) {
            Log::error('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function findThemes()
    {
        try {
            $this->bot->onText('/theme {date?}', function (Context $ctx) {
                $message =  TelegramChatTheme::getMessageWithThemesByDataFormat($ctx->getChatID(), $ctx->var('date'), 'd.m.y');
                $ctx->reply($message);
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    private function shopButton(Context $ctx)
    {
        $authorId = PseudoCrypt::unhash($ctx->var('author_hash'));
        $author = Author::find($authorId);
        if (!$author) {
            return null;
        }

        $description = ($author->name ?? 'Магазин') . "\n" . ($author->about ?? '');
        $link = 'https://t.me/' . config('telegram_bot.bot.botName') . '/' . config('telegram_bot.bot.marketName') .  '/?startapp=' . $authorId;
        $menu = Menux::Create('link')->inline();
        $menu->row()->uBtn('Открыть магазин', $link);
        $ctx->reply($description . "\n\n", $menu);
    }

    private function getTariffButtonName(?string $title, ?string $price, ?string $period): string
    {
        return ($title ? $title . ' — ' : '') . ($price ? $price . '₽' : '') . ($price && $period ? '/' : '') . ($period ? 'Дней:' . $period : '');
    }

    private function tariffBuyButton(Context $ctx)
    {
        $tariff = Tariff::where('inline_link', $ctx->var('tariff_hash'))->first();
        if (!$tariff) {
            return null;
        }
        $community = $tariff->community;
        if (!$community) {
            return null;
        }

        $variant = $community->tariff->variants()
            ->where('isActive', true)
            ->where('isPersonal', false)
            ->where('inline_link', $ctx->var('variant_hash'))
            ->first();
        if (!$variant) {
            $ctx->reply("Тарифы не установлены для сообщества\n\n");
            return null;
        }

        $userBuyedTariff = TelegramUserTariffVariant::findBuyedTariffByTelegramUserId($ctx->getUserID(), $variant->id);
        if ($userBuyedTariff) {
            if ($variant->isTest) {
                $ctx->reply("Вы уже использовали тестовый период данного тарифа\n\n");
            } else {
                $ctx->reply("Данный тариф уже куплен, автопродление включено.\n\n");
            }
            return null;
        }

        $menu = Menux::Create('link')->inline();

        $menu->row()->uBtn('Оплатить тариф', Tariff::preparePaymentLink($community->tariff->inline_link, $variant->isTest, $ctx->getUserID()));
        $buttonName = $this->getTariffButtonName($variant->title, $variant->price, $variant->period);
        $ctx->reply($buttonName . "\n\n", $menu);
    }

    public function donateButtons($ctx)
    {
        Log::debug('In start donate');
        $donate = Donate::where('inline_link', $ctx->var('donate_hash'))->first();
        $menu = Menux::Create('link')->inline();
        if (!$donate) return;
        $data = [
            'amount' => $ctx->var('amount'),
            'donate_id' => $donate->id,
            'telegram_user_id' => $ctx->getUserID(),
        ];
        $dataRandom = [
            'min_price' => $donate->getRandomSumVariant()->min_price,
            'max_price' => $donate->getRandomSumVariant()->max_price,
            'donate_id' => $donate->id,
            'telegram_user_id' => $ctx->getUserID(),
        ];
        $menu->row()->uBtn('Внести донат', $ctx->var('amount') == 0 ? $donate->getDonatePaymentLinkRandom($dataRandom) : $donate->getDonatePaymentLink($data));
        $ctx->reply('Ссылка для доната ' . "\n\n", $menu);
    }


    protected function startOnGroup()
    {
        try {
            $this->bot->onCommand('start' . $this->bot->botFullName, function (Context $ctx) {
                $ctx->reply('Здравствуйте, ' . $ctx->getFirstName() . "!");
                $this->save_log(
                    TelegramBotActionHandler::START_ON_GROUP,
                    TelegramBotActionHandler::ACTION_SEND_HELLO_MESSAGE,
                    $ctx);
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
                    $this->save_log(
                        TelegramBotActionHandler::GET_TELEGRAM_USER_ID,
                        TelegramBotActionHandler::ACTION_SEND_TELEGRAM_ID,
                        $ctx);
                }
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function getChatId()
    {
        $this->bot->onCommand('chatid', function (Context $ctx) {
            $ctx->reply($ctx->getChatID());
            $this->save_log(
                TelegramBotActionHandler::GET_CHAT_ID,
                TelegramBotActionHandler::ACTION_SEND_CHAT_TELEGRAM_ID,
                $ctx);
        });
    }

    protected function getChatType()
    {
        $this->bot->onCommand('type', function (Context $ctx) {
            $ctx->reply($ctx->getChatType());
            $this->save_log(
                TelegramBotActionHandler::GET_CHAT_TYPE,
                TelegramBotActionHandler::ACTION_SEND_CHAT_TYPE,
                $ctx);
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
                $this->save_log(
                    TelegramBotActionHandler::SET_COMMAND,
                    TelegramBotActionHandler::ACTION_SET_COMMAND,
                    $ctx);
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
                    $this->save_log(
                        TelegramBotActionHandler::TARIFF_ON_USER,
                        TelegramBotActionHandler::ACTION_SEND_TARIFF,
                        $ctx);
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
                $this->save_log(
                    TelegramBotActionHandler::TARIFF_ON_CHAT,
                    TelegramBotActionHandler::ACTION_SEND_TARIFF_TO_CHAT,
                    $ctx);
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function inlineCommand()
    {
        try {
            $donates = Donate::active()->get();
            foreach ($donates as $donate) {
                if (!$donate)
                    return false;
                if (!$donate->inline_link)
                    return false;
                $this->inlineQuery($donate);
            }
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /**
     * Support command
     *
     * @return void
     */
    protected function support()
    {
        try {
            $supportBase = function (Context $ctx) {
                if ($this->isPrivateBot($ctx)) {
                    $ctx->replyHTML('Пожалуйста, опишите что случилось одним сообщением и оставьте ' . "\n"
                        . 'ваши контакты для обратной связи:' . "\n\n"
                        . ' • телефон' . "\n"
                        . ' • почту' . "\n"
                        . ' • UserName Telegram' . "\n\n"
                        . '<b>Пример: </b>' . "\n"
                        . self::SUPPORT_MESSAGE . ' 84950000000 your@email.ru  UserName текст ' . "\n");
                }
            };

            $supportMessage = function (Context $ctx) {
                $message = $ctx->var('message');
                if ($message != '' ?? $this->isPrivateBot($ctx)) {

                    preg_match_all('/([^ ]*)[ ]*([^ ]*)[ ]*([^ ]*)[ ]*(.*)/s', trim($message), $res, PREG_SET_ORDER );
                    $html = '<p>Телефон: "' . ($res[0][1] ?? '') . '"<br>
                            Почта: "' . ($res[0][2] ?? '') . '"<br>
                            Юзернейм: "' . ($res[0][3] ?? '') . '"<br>
                            Текст: "' . ($res[0][4] ?? '') . '"</p>';

                    SendEmails::dispatch('info@spodial.com', 'Обращение в службу поддержки', 'Cервис Spodial', $html);

                    SendTeleMessageToChatFromBot::dispatch(config('telegram_bot.bot.botName'), '6172841852', $message);
                    $ctx->replyHTML('Жаль, что вы с этим столкнулись! Я передал сообщение в службу  ' . "\n"
                        . 'поддержки, с вами свяжутся при первой же возможности. ');

                    /*
                     * Ответ службы поддержки отправляется пользователю в телеграм от бота и на почту
                     * отправлять на почту info@spodial.com и в телеграм @infospodial
                     */
                } else {
                    $ctx->replyHTML('Чтобы отправить обращение напишите ' . "\n"
                        . '<b> Пример: </b> ' . "\n"
                        . " \issue текст и контактные данные. ");
                }
            };

            $this->bot->onHears(self::SUPPORT, $supportBase);
            $this->bot->onCommand('support', $supportBase);
            $this->bot->onText(self::SUPPORT_MESSAGE . '{message}', $supportMessage);
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function getSpodial()
    {
        try {
            $this->bot->onCommand(self::CONNECT_CHAT_TO_SPODIAL, function (Context $ctx) {
                $menu = Menux::Create('links')->inline();
                $menu->row()->uBtn('Перейти в личный кабинет', route('main'));
                $ctx->reply('Проще всего подключить новый чат на нашей платформе. Там же вы'
                    . 'сможете произвести все настройки моей работы с вашим чатом. ' . "\n\n"
                    , $menu);
            });

        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    private function inlineShop()
    {
        try {
            $this->bot->onInlineQuery('s-{authorId}', function (Context $ctx) {
                $authorId = PseudoCrypt::unhash($ctx->var('authorId'));
                $author = Author::find($authorId);
                if (!$author) {
                    return 0;
                }

                $result = new Result();
                $article = new Article(1);
                $message = new InputTextMessageContent();

                $theme = $author->name ?? '';
                $description = $author->about ?? 'Магазин';

                $message->text($theme . "\n" . $description)->parseMode('HTML');
                $article->title($theme)
                        ->description($description)
                        ->inputMessageContent($message);

                if ($author->photo) {
                    $article->thumbUrl(config('app.url') . '/' . $author->photo);
                }
                $menu = Menux::Create('a')->inline();
                $menu->row()->btn('Смотреть товары автора', 'shop-' . $ctx->var('authorId') . '_author');

                $article->keyboard($menu->getAsObject());
                $result->add($article);

                $ctx->Api()->answerInlineQuery([
                    'inline_query_id' => $ctx->getInlineQueryID(),
                    'results' => (string)$result,
                ]);

            });
        } catch (\Exception $e) {
            Log::error('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    private function inlineTariffQuery()
    {
        try {
            $this->bot->onInlineQuery('t-{tariffHash}-{communityHash}', function (Context $ctx) {
                $communityId = PseudoCrypt::unhash($ctx->var('communityHash'));
                $community = Community::find($communityId);
                $tariff = Tariff::query()
                    ->where('inline_link', $ctx->var('tariffHash'))
                    ->where('community_id', $communityId)
                    ->first();

                $result = new Result();
                $article = new Article(1);
                $message = new InputTextMessageContent();

                $image = $tariff->main_image ?? null;
                $description = strip_tags($tariff->main_description) ?? null;
                if (empty($description)) {
                    $description = 'Тариф';
                }
                $message->text($description . '<a href="' . config('app.url') . '/' . $image . '">&#160</a>')
                        ->parseMode('HTML');
                $article->title($community->title ?? 'Тариф')
                        ->description($description)
                        ->inputMessageContent($message);
                if ($image) {
                    $article->thumbUrl(config('app.url') . '/' . $image);
                }

                $menu = Menux::Create('a')->inline();
                $variants = $community->tariff->variants()
                    ->where('isActive', true)
                    ->where('isPersonal', false)
                    ->get();
                if ($variants->count() == 0) {
                    $ctx->reply("Тарифы не установлены для сообщества\n\n");
                    return null;
                }

                foreach ($variants as $variant) {
                    $buttonName = $this->getTariffButtonName($variant->title, $variant->price, $variant->period);
                    $menu->row()->btn($buttonName, 'tariff-' . $community->tariff->inline_link . '_' . $variant->inline_link);
                }

                $article->keyboard($menu->getAsObject());
                $result->add($article);
                Log::debug('Sending answer, inlineTariffQuery', [$result, $message, $article]);
                $ctx->Api()->answerInlineQuery([
                    'inline_query_id' => $ctx->getInlineQueryID(),
                    'results' => (string)$result,
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    private function inlineQuery($donate)
    {
        try {
            $this->bot->onInlineQuery($donate->inline_link, function (Context $ctx) use ($donate) {
                Log::debug('start donate callback query ' . $donate->inline_link, [$donate]);

                $result = new Result();
                $article = new Article(1);
                $message = new InputTextMessageContent();

                $image = $donate->image;
                $description = trim(strip_tags($donate->description)) ? strip_tags($donate->description) : 'Донат';
                $message->text($description . '<a href="' . config('app.url') . '/' . $image . '">&#160</a>');

                $message->parseMode('HTML');
                $article->title(trim($donate->title) ? $donate->title : 'Донат');
                $article->description(mb_strlen($description)>55 ? mb_strimwidth($description, 0, 55, "...") : $description);

                $article->inputMessageContent($message);
                $article->thumbUrl('' . config('app.url') . '/' . $image);

                $menu = Menux::Create('a')->inline();
                $variants = $donate->variants()->get();
                foreach ($variants as $variant) {
                    if ($variant->price && $variant->isActive !== false) {
                        $key = array_search($variant->currency, Donate::$currency);

                        $currencyLabel = Donate::$currency_labels[$key];

                        if (strip_tags($variant->description)) {
                            $menu->row()->btn(
                                $variant->price . $currencyLabel . ' — ' . strip_tags($variant->description),
                                'donate-' . $donate->inline_link . '_' . $variant->price
                            );
                        } else {
                            $menu->row()->btn($variant->price . $currencyLabel, 'donate-' . $donate->inline_link . '_' . $variant->price);
                        }
                    } elseif ($variant->min_price && $variant->max_price && $variant->isActive !== false) {
                        $variantDesc = strip_tags($variant->description) != '' ? strip_tags($variant->description) : 'Произвольная сумма';
                        $menu->row()->btn($variantDesc, 'donate-' . $donate->inline_link . '_0');
                    }
                }

                $article->keyboard($menu->getAsObject());
                $result->add($article);
                Log::debug('Sending query answer', [$result, $message, $article]);
                $ctx->Api()->answerInlineQuery([
                    'inline_query_id' => $ctx->getInlineQueryID(),
                    'results' => (string)$result,
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    private function getDonateData()
    {
        try {
            $this->bot->onAction('{donate_hash}_{amount}', function (Context $ctx) {
                $botName = config('telegram_bot.bot.botName');
                Log::debug('In donate answer query: '. $botName);

                $ctx->ansCallback('', false, 't.me/' . $botName . '?start=' . $ctx->var('donate_hash') . '_' . $ctx->var('amount'));
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function reputation()
    {
        try {
            $reputation = function (Context $ctx) {
                if ($this->isPrivateMessageToBot($ctx)) {
                    if (TelegramUser::isCommunityUserOwner($ctx->getUserID())) {
                        $menu = Menux::Create('inline_keyboard')->inline();
                        $communities = $this->communityRepo->getCommunitiesForMemberByTeleUserId($ctx->getChatID());
                        if ($communities->first()) {
                            foreach ($communities as $community) {
                                if ($community->communityReputationRule && $community->communityReputationRule->show_rating_tables) {
                                    $menu->row()->btn($community->title, 'community_rep ' . $community->id);
                                }
                            }
                            $ctx->reply('Выберите один из чатов с включенной репутацией.', $menu);
                        } else {
                            $ctx->reply('У вас нет чатов');
                        }
                    }
                }
            };

            $reputationCommunities = function (Context $ctx) {
                $communityId = (int)$ctx->var('id');
                $communities = $this->communityRepo->getCommunitiesForOwnerByTeleUserId($ctx->getChatID());
                $community = $communities->find($communityId);

                if ($community) {
                    $reputationUsers = TelegramUserReputation::getUsersByCondition('community_id', $communityId);
                    $str = '';
                    $c = 1;
                    if ($reputationUsers) {
                        foreach ($reputationUsers as $userRep) {
                            $str .= $c . '. ' . $userRep->telegramUser->getTelegramUserName() . ' ' . $userRep->reputation_count . "\n\n";
                            $c++;
                        }
                    }

                    $ctx->reply('Рейтинг ТОП-10 участников чата ' . $community->title . "\n\n" . $str);
                } else {
                    $ctx->reply('Рейтинг ТОП-10 участников чата ' . $communityId);
                }
            };

            $this->bot->onText(self::REPUTATION, $reputation);
            $this->bot->onAction('community_rep {id}', $reputationCommunities);
        } catch (\Exception $e) {
            Log::error('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function addNewGroup()
    {
        try {
            $base = function (Context $ctx) {
                log::info('____________addNewGroup in chat bot:');
                // in private to bot
                if ($this->isPrivateMessageToBot($ctx)) {
                    $link = 'https://t.me/' . trim($this->bot->botFullName, '@') . '?' . self::BOT_INVITE_TO_GROUP_SETTINGS;
                    log::info('link:' . $link);

                    $menu = Menux::Create('links')->inline();
                    $menu->row()->uBtn('Добавить бота в чат', $link);
                    $title = 'Добавьте  ' . $this->bot->botFullName . ' в чат и дайте ему права администратора. ';
                    $ctx->reply($title, $menu);

                    $data = [
                        TelegramUser::TELEGRAM_ID => $ctx->getChatID(),
                        TelegramUser::FIRST_NAME  => $ctx->getChat()->firstName(),
                        TelegramUser::LAST_NAME   => $ctx->getChat()->lastName(),
                        TelegramUser::USER_NAME   => $ctx->getChat()->username(),
                    ];

                    InitCommunityConnectionJob::dispatch('group', json_encode($data, JSON_UNESCAPED_UNICODE));
                }
            };

            $this->bot->onText(self::ADD_NEW_CHAT_TEXT, $base);
//            $this->bot->onCommand(self::ADD_NEW_CHAT_COMMAND, $base);
        } catch (\Exception $e) {
            Log::error('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
//            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /**
     * @return void
     */
    protected function helpOnChat()
    {
        try {
            $base = function (Context $ctx) {
                // in private to bot
                if ($this->isPrivateMessageToBot($ctx)) {
                    $communities = $this->communityRepo->getCommunitiesForMemberByTeleUserId($ctx->getChatID());
                    if ($communities->first()) {
                        $menu = Menux::Create('links')->inline();
                        /** @var Community $community */
                        $basesCount = 0;
                        foreach ($communities as $community) {
                            $link = $community->getPublicKnowledgeLink();
                            if ($link) {
                                log::info('title:' . $community->title . 'link' . $link);
                                $menu->row()->uBtn($community->title, $link);
                                $basesCount++;
                            }
                        }
                        if ($basesCount) {
                            $ctx->reply('Выберите сообщество', $menu);
                        } else {
                            $ctx->reply('У сообщества еще нет базы знаний');
                        }
                    } else {
                        $ctx->reply('Вы не состоите в сообществах');
                    }
                } else {
                    $community = $this->communityRepo->getCommunityByChatId($ctx->getChatID());
                    if($community === null) {
                        log::error('command database ! community is null' );
                       return;
                    }

                    $link = $community->getPublicKnowledgeLink();
                    if ($link) {
                        $menu = Menux::Create('links')->inline();
                        $menu->row()->uBtn($community->title, $link);
                        $ctx->reply('Ссылка на Базу Знаний по сообществу: ' . "\n\n", $menu);
                    } else {
                        $ctx->reply('У сообщества еще нет базы знаний');
                    }
                }

                $this->save_log(
                    TelegramBotActionHandler::BASE_ON_CHAT,
                    TelegramBotActionHandler::SEND_BASE_IN_CHAT,
                    $ctx);
            };

            $this->bot->onText(self::KNOWLEDGE_BASE, $base);
            $this->bot->onCommand(self::KNOWLEDGE_BASE_BOT, $base);
        } catch (\Exception $e) {
            Log::error('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function helpOnBot()
    {
        try {
            $this->bot->onCommand('qa', function (Context $ctx) {
                $menu = Menux::Create('links')->inline();
                $communities = $this->communityRepo->getCommunitiesForMemberByTeleUserId($ctx->getChatID());
                if ($communities->first()) {
                    foreach ($communities as $community) {
                        $link = $community->getPublicKnowledgeLink();
                        $menu->row()->uBtn($community->title, $link);
                    }
                    $ctx->reply('Выберите сообщество', $menu);
                } else $ctx->reply('Вы не состоите в сообществах');
                $this->save_log(
                    TelegramBotActionHandler::HELP_ON_BOT,
                    TelegramBotActionHandler::SEND_HELP_ON_BOT,
                    $ctx);
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
                        $this->save_log(
                            TelegramBotActionHandler::DONATE_ON_CHAT,
                            TelegramBotActionHandler::SEND_DONATE_IN_CHAT,
                            $ctx);
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
                        $this->save_log(
                            TelegramBotActionHandler::DONATE_ON_USER,
                            TelegramBotActionHandler::SEND_DONATE_USER,
                            $ctx);
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
                $this->save_log(
                    TelegramBotActionHandler::SUBSCRIPTION_SEARCH,
                    TelegramBotActionHandler::SEND_SUBSCRIPTION_ID,
                    $ctx);
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
                    $ctx->reply('Ваш тариф уже активирован, чтобы получить ссылку на ресурс пройдите в раздел "Мои чаты".');
                }

                if ($payment && $payment->type == 'tariff' && ($payment->status == 'CONFIRMED' || $payment->status == 'AUTHORIZED')) {

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
                $this->save_log(
                    TelegramBotActionHandler::SET_TARIFF_FOR_USER_BY_PAID_ID,
                    TelegramBotActionHandler::ACTION_SET_TERIFF_TO_USER,
                    $ctx);
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
                $this->save_log(
                    TelegramBotActionHandler::MATERIAL_AID,
                    TelegramBotActionHandler::ACTION_SEND_MATERIAL_AID,
                    $ctx);
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function personalArea()
    {
        try {
            $cabinet = function (Context $ctx) {
                $menu = Menux::Create('links')->inline();
                $menu->row()->uBtn('Перейти в личный кабинет', config('app.frontend_url'));
                $ctx->reply('Для того чтобы перейти в личный кабинет перейдите по ссылке', $menu);
                $this->save_log(
                    TelegramBotActionHandler::PERSONAL_AREA,
                    TelegramBotActionHandler::ACTION_SEND_PERSONAL_AREA,
                    $ctx);
            };
            $this->bot->onText(self::CABINET, $cabinet);
            $this->bot->onCommand(self::CABINET_COMMAND, $cabinet);
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function faq()
    {
        try {
            $this->bot->onCommand('/help', function (Context $ctx) {
                $menu = Menux::Create('links')->inline();
                $menu->row()->uBtn('Помощь', route('faq.index'));
                $ctx->reply('Для того чтобы получить помощь перейдите по ссылке', $menu);
                $this->save_log(
                    TelegramBotActionHandler::FAQ,
                    TelegramBotActionHandler::ACTION_SEND_FAQ,
                    $ctx);
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    protected function mySubscriptions()
    {
        try {
            $this->bot->onHears(self::MY_SUBSRUPTION, function (Context $ctx) {
                $menu = Menux::Create('links')->inline();
                $communities = $this->communityRepo->getCommunitiesForMemberByTeleUserId($ctx->getChatID());
                if ($communities->first()) {
                    foreach ($communities as $community) {
                        $menu->row()->btn($community->title ?? 'btn', 'subscription-' . $community->connection_id);
                    }
                    $ctx->reply('Выберите чат', $menu);
                } else $ctx->reply('У вас нет чатов');
                $this->save_log(
                    TelegramBotActionHandler::MY_SUBSCRIPTION,
                    TelegramBotActionHandler::ACTION_SEND_MY_SUBSCRIPTION,
                    $ctx);
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
                $this->save_log(
                    TelegramBotActionHandler::KNOWLEDGE_SEARCH,
                    TelegramBotActionHandler::ACTION_SEND_KNOWLEDGE,
                    $ctx);
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
        return $context;
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

                $category = Category::firstOrCreate(['title' => 'ЧАТБОТ', 'community_id' => $community->id], [
                    'variant' => 'permanent',
                ]);
                $this->manageQuestionService->setUserId($community->owner);
                $this->manageQuestionService->createFromArray([
                    'community_id' => $community->id,
                    'question' => [
                        'context' => ArrayHelper::getValue($data, 'q'),
                        'is_public' => false,
                        'is_draft' => false,
                        'category_id' => $category->id,
                        'answer' => [
                            'context' => ArrayHelper::getValue($data, 'a'),
                            'is_draft' => false,
                        ],
                    ],
                ]);
                $ctx->reply("Вопрос ответ сохранен в сообщество: {$community->title}");
                $this->save_log(
                    TelegramBotActionHandler::SAVE_FORWARD_MESSAGE_IN_BOT_CHAT_AS_QA,
                    TelegramBotActionHandler::ACTION_SAVE_QUESTION_ANSWER,
                    $ctx);
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
                    $this->save_log(
                        TelegramBotActionHandler::UNSUBSCRIBE,
                        TelegramBotActionHandler::ACTION_UNSUBSCRIBE,
                        $ctx);
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
                $this->save_log(
                    TelegramBotActionHandler::ACCESS,
                    TelegramBotActionHandler::ACTION_SEND_ACCESS,
                    $ctx);
            });
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    private
    function extend()
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
                                        'telegram_user_id' => $ctx->getUserID(),
                                        'inline_link' => PseudoCrypt::hash($variant->id, 8),
                                    ]));
                                }
                            }
                            $ctx->replyHTML($text, $menu);
                            $this->save_log(
                                TelegramBotActionHandler::EXTEND,
                                TelegramBotActionHandler::ACTION_EXTEND,
                                $ctx);
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
            $trial = strpos($ctx->var('paymentId'), 'trial');
            $payId = PseudoCrypt::unhash( str_replace('trial', '', $ctx->var('paymentId') ));
            $payment = Payment::where('id', $payId)->where('activated', false)->first();
            if (!$payment) {
                return false;
                }

            Telegram::paymentUser(
                $ctx->getUserID(),
                $ctx->getUsername(),
                $ctx->getFirstName(),
                $ctx->getLastName(),
                $ctx->var('paymentId'),
                $this->bot->getExtentionApi()
            );

            if ($trial === false) {
                if ($payment && $payment->type == 'tariff') {
                    $link = $this->createAndSaveInviteLink($payment->community->connection);
                    $invite = ($link)
                        ? "\n" . 'Чтобы вступить в сообщество, нажмите сюда: <a href="' . $link . '">Подписаться</a>' : '';

                    $message = $payment->community->tariff->thanks_message ?? '';

                    $image = ($payment->community->tariff->getThanksImage()) ? ' <a href="' . route('main') . $payment->community->tariff->getThanksImage()->url . '">&#160</a>' : '';
                    $variant = $payment->community->tariff->variants()->find($payment->payable_id);
                    if ($variant->isActive === true) {
                        $variantName = $variant->title ?? '{Название тарифа}';
                        $date = date('d.m.Y H:i', strtotime("+$variant->period days")) ?? 'Неизвестно';
                    }

                    $defMassage = "\n\n" . 'Сообщество: ' . $payment->community->title . "\n" . 'Выбранный тариф: ' . $variantName . "\n" . 'Cрок окончания действия: ' . $date . "\n";
//                    $ctx->replyHTML($image . $message . $defMassage . $invite); //отключить приветствие в боте после подписки
                    $ctx->replyHTML($defMassage . $invite);
                    //todo отправить сообщение автору через личный чат с ботом,
                    $ty = TelegramUser::where([
                        'telegram_id' => $ctx->getUserID()
                    ])->first();

                    $payerName = $ty->publicName() ?? '';
                    $tariffName = $variant->title ?? '';
                    $tariffCost = ($payment->amount / 100) ?? 0;
                    $tariffEndDate = Carbon::now()->addDays($variant->period)->format('d.m.Y H:i') ?? '';
                    $communityTitle = strip_tags($payment->community->title);
                    $variantPeriod = $variant->period . ' ' . trans_choice('plurals.days', $variant->period, [], 'ru');

                    if ($payment->comment !== 'trial') {
                        $message = "Участник $payerName оплатил $tariffName в сообществе $communityTitle, стоимость $tariffCost рублей, действует до $tariffEndDate г.";
                    } else {
                        $message = "Участник $payerName присоединился к сообществу $communityTitle на Пробный период продолжительностью $variantPeriod." . "\n" . "Действует до $tariffEndDate";
                    }
                    Log::info('send tariff pay message to own author chat bot', [
                        'message' => $message
                    ]);

                    $authorTeleUserId = $payment->community->connection->telegram_user_id ?? 0;
                    SendTeleMessageToChatFromBot::dispatch(config('telegram_bot.bot.botName'), $authorTeleUserId, $message);
                }
            } else {
                $community = $payment->community;
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
                    if (!isset($variantName)) {
                        $ctx->replyHTML('Тестовый тариф отключен.'); 
                        return false;
                    }
                    $defMassage = "\n\n" . 'Сообщество: ' . $community->title . "\n" . 'Выбранный тариф: ' . $variantName . "\n" . 'Cрок окончания действия: ' . $date . "\n";

//                    $ctx->replyHTML($image . $message . $defMassage . $invite); //отключить приветствие в боте после подписки
                    $ctx->replyHTML($defMassage . $invite);
                } else $ctx->replyHTML('Сообщество не существует');
            }
        } catch (\Exception $e) {
            return $ctx->reply('Что-то пошло не так, пожалуйста обратитесь в службу поддержки.' . 'Ошибка:'
                . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    private
    function createAndSaveInviteLink($telegramConnection)
    {
        try {
            $invite = $this->bot->getExtentionApi()->createAdditionalLink($telegramConnection->chat_id);
            $link = ($invite->object())->result->invite_link;
            $telegramConnection->update([
                'chat_invite_link' => $link
            ]);
            return $link;
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    private
    function createMenu()
    {
        try {
            $keybord = new Keyboard(Keyboard::INLINE);
            Menux::Create('menu', 'main') //  в рамках группы
//                ->row(Keyboard::btn('menu', 'calendar.ignore'), Keyboard::btn('Вт', 'calendar.ignore'));
                ->row()->btn(self::CABINET) // +
                ->row()->btn(self::KNOWLEDGE_BASE)
                ->row()->btn(self::SUPPORT)
                ->row()->btn('Подключить чат к Spodial');
            Menux::Create('menuCustom', 'custom')
                ->row(
                    Keyboard::btn(self::ADD_NEW_CHAT_TEXT, 'calendar.ignore'),
                    Keyboard::btn(self::CABINET))
                ->row(
                    Keyboard::btn(self::SUPPORT),
                    Keyboard::btn(self::KNOWLEDGE_BASE),
                    Keyboard::btn(self::MY_SUBSRUPTION)
                );
//                ->row()->btn(self::CABINET)
//                ->row()->btn(self::KNOWLEDGE_BASE)
//                ->row()->btn(self::MY_SUBSRUPTION)
//                ->row()->btn(self::SUPPORT);

            Menux::Create('menuOwner', 'owner')
//                ->row(Keyboard::btn('menuOwner'), Keyboard::btn('Вт', 'calendar.ignore'));
                    ->row(
                        Keyboard::btn(self::ADD_NEW_CHAT_TEXT, 'calendar.ignore'),
                        Keyboard::btn(self::CABINET))
                    ->row(
                        Keyboard::btn(self::SUPPORT),
                        Keyboard::btn(self::KNOWLEDGE_BASE),
                        Keyboard::btn(self::MY_SUBSRUPTION)
                    );
//                ->row()->btn(self::CABINET)
//                ->row()->btn(self::KNOWLEDGE_BASE)
//                ->row()->btn(self::SUPPORT)
//                ->row()->btn(self::MY_SUBSRUPTION);
//                ->row()->btn(self::REPUTATION);
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    private function tariffButton($community, $userId = NULL)
    {
        try {
            $menu = Menux::Create('a')->inline();
            $text = 'Доступные тарифы';
            $variants = $community->tariff->variants()
                ->where('isActive', true)
                ->where('isPersonal', false)
                ->where('price', '>', 0)
                ->get();
            if ($variants->count() == 0) {
                return ['Тарифы не установлены для сообщества', ''];
            }
            foreach ($variants as $variant) {
                $price = ($variant->price) ? $variant->price . '₽' : '';
                $title = ($variant->title) ? $variant->title . ' — ' : '';
                $period = ($variant->period) ? '/Дней:' . $variant->period : '';
                $menu->row()->Btn($title . $price . $period, 'tariff-' . $community->tariff->inline_link);
            }
            return [$text, $menu];
        } catch (\Exception $e) {
            $this->bot->getExtentionApi()->sendMess(env('TELEGRAM_LOG_CHAT'), 'Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Отправляет сообщение в группу с донатами
     * @param int $chatId
     * @param int $donateId
     */
    public
    function sendDonateMessage(int $chatId, int $donateId)
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
    public
    function sendTariffMessage(Community $community)
    {
        try {
            $tariff = $community->tariff;
            foreach ($tariff->variants->sortBy('price') as $variant) {
                if ($variant->isActive !== false && $variant->isPersonal == false) {
                    $data = [
                        'amount' => $variant->price,
                        'currency' => 0,
                        'type' => 'tariff',
                        'telegram_user_id' => NULL,
                        'inline_link' => PseudoCrypt::hash($variant->id, 8),
                    ];

                    $button[] = [[
                        'text' => $variant->title . ' — ' . $variant->price . ' ₽' . ' / ' . $variant->period . ' ' . Declination::defineDeclination($variant->period),
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
    public
    function sendMessageFromBotWithTariff(int $chatId, string $textMessage, Community $community)
    {
        try {
            $tariff = $community->tariff;
            foreach ($tariff->variants as $variant) {
                if ($variant->price !== 0 && $variant->isActive !== false && $variant->isPersonal == false) {
                    $data = [
                        'amount' => $variant->price,
                        'currency' => 0,
                        'type' => 'tariff',
                        'telegram_user_id' => NULL,
                        'inline_link' => PseudoCrypt::hash($variant->id, 8),
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

    private
    function getCommandsListAsString(): string
    {
        $text = '';
        foreach ($this->availableBotCommands as $command => $description) {
            $text .= $command . ' - ' . $description . "\n";
        }
        return $text;
    }

    public
    function save_log(
        string  $event,
        string  $action,
        Context $context
    )
    {
        Log::channel('telegram_bot_action_log')->
        log('info', '', [
            'event' => $event,
            'action' => $action,
            'telegram_id' => $context->getUserID(),
            'chat_id' => $context->getChatID(),
        ]);
    }
}
