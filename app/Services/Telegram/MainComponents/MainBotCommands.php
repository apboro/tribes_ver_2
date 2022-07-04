<?php

namespace App\Services\Telegram\MainComponents;

use App\Helper\PseudoCrypt;
use App\Jobs\CheckDaysForUsers;
use App\Models\Community;
use App\Models\Donate;
use App\Models\Payment;
use App\Models\TelegramUser;
use App\Repositories\Community\CommunityRepositoryContract;
use App\Repositories\Payment\PaymentRepositoryContract;
use App\Repositories\TelegramConnection\TelegramConnectionRepositoryContract;
use App\Services\Telegram;
use App\Services\Telegram\MainBot;
use App\Traits\Declination;
use Askoldex\Teletant\Context;
use Askoldex\Teletant\Addons\Menux;
use Askoldex\Teletant\Entities\Inline\Article;
use Askoldex\Teletant\Entities\Inline\Result;
use Askoldex\Teletant\Entities\Inline\InputTextMessageContent;
use Askoldex\Teletant\Exception\MenuxException;
use Askoldex\Teletant\Exception\TeletantException;


class MainBotCommands
{
    protected MainBot $bot;
    protected CommunityRepositoryContract $communityRepo;
    protected TelegramConnectionRepositoryContract $connectionRepo;
    protected PaymentRepositoryContract $paymentRepo;


    public function __construct(
        TelegramConnectionRepositoryContract $connectionRepo,
        CommunityRepositoryContract $communityRepo,
        PaymentRepositoryContract $paymentRepo

    )
    {
        $this->paymentRepo = $paymentRepo;
        $this->connectionRepo = $connectionRepo;
        $this->communityRepo = $communityRepo;
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
        'donateOnChat',
        'donateOnUser',
        'materialAid',
        'personalArea',
        'faq',
        'mySubscriptions',
        'subscriptionSearch',
        'setTariffForUserByPayId'
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
        $this->createMenu();
        $this->bot->onText('/start {paymentId?}', function (Context $ctx) {
            try {
                $users = TelegramUser::where('user_id', '!=', NULL)->where('telegram_id', $ctx->getUserID())->get();

                if ($users->first()) {
                    if (str_split($ctx->getChatID(), 1)[0] !== '-') {
                        $ctx->replyHTML('Добро пожаловать в главное меню, ' . $ctx->getUsername() . '! Я бот сервиса по монетизации Telegram-каналов и чатов.' . "\n\n"
                            . 'Ссылка на сайт ' . route('main') . "\n"
                            . 'Создание и настройка проектов происходит в веб кабинете.' . "\n\n"
                            . 'Вот список доступных для вас команд:' . "\n"
                            . '/start - Начало работы с ботом' . "\n"
                            . '/myid - показывает ваш уникальный ID', Menux::Get('main'));
                    } else $ctx->reply('Здравствуйте, вас приветствует TestBot');
                } else {
                    if (str_split($ctx->getChatID(), 1)[0] !== '-') {
                        $ctx->replyHTML('Здравствуйте, ' . $ctx->getUsername()
                            . '! Добро пожаловать в сообщество.' . "\n\n"
                            . 'Вот список доступных для вас команд:' . "\n"
                            . '/start - Начало работы с ботом' . "\n"
                            . '/donate - если желаете оказать помощь сообществу в котором состоите', Menux::Get('custom'));
                    }
                }
                if (!empty($ctx->var('paymentId'))) {
                    $this->connectionTariff($ctx);
                }
            } catch (TeletantException $e) {
                return $ctx->reply('Что-то пошло не так, пожалуйста обратитесь в службу поддержки.' . 'Ошибка:'
                    . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
            }
        });
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
                        ? "\n" . 'Пригласительная ссылка на ресурс: <a href="' . $link . '">Подписаться</a>' : '';

                    $message = $payment->community->tariff->thanks_description ?? '';

                    $image = ($payment->community->tariff->getThanksImage()) ? ' <a href="' . route('main') . $payment->community->tariff->getThanksImage()->url . '">&#160</a>' : '';
                    $variant = $payment->community->tariff->variants()->find($payment->payable_id);
                    if ($variant->isActive === true) {
                        $variantName = $variant->title ?? '{Название тарифа}';
                        $date = date('d.m.Y H:i', strtotime("+$variant->period days")) ?? 'Неизвестно';
                    }

                    $defMassage = "\n\n" . 'Выбранный тариф: ' . $variantName . "\n" . 'Cрок окончания действия: ' . $date . "\n";
                    $ctx->replyHTML($image . $message . $defMassage . $invite);
                }
            } else {
                $communityId = str_replace('trial', '', $ctx->var('paymentId'));
                $community = $this->communityRepo->getCommunityById($communityId);
                if ($community) {
                    $link = $this->createAndSaveInviteLink($community->connection);
                    $invite = ($link) ? "\n" . 'Пригласительная ссылка на ресурс: <a href="' . $link . '">Подписаться</a>' : '';

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
        } catch (TeletantException $e) {
            return $ctx->reply('Что-то пошло не так, пожалуйста обратитесь в службу поддержки.' . 'Ошибка:'
                . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    private function createAndSaveInviteLink($telegramConnection)
    {
        $invite = $this->bot->getExtentionApi()->createInviteLink($telegramConnection->chat_id);
        $telegramConnection->update([
            'chat_invite_link' => $invite
        ]);
        return $invite;
    }

    private function createMenu()
    {
        try {
            Menux::Create('menu', 'main')
                ->row()->btn('🚀Личный кабинет')->btn('🔧Помощь')
                ->row()->btn('❗Оказать материальную помощь')->btn('📂Мои подписки')
                ->row()->btn('🔍Найти подписку');

            Menux::Create('menuCustom', 'custom')
                ->row()->btn('🚀Личный кабинет')->btn('🔧Помощь')
                ->row()->btn('❗Оказать материальную помощь')->btn('📂Мои подписки');
        } catch (MenuxException $e) {
        }
    }

    protected function startOnGroup()
    {
        $this->bot->onCommand('start' . $this->bot->botFullName, function (Context $ctx) {
            $ctx->reply('Здравствуйте, ' . $ctx->getFirstName() . "! \n"
                . 'Список доступных для вас команд:' . "\n"
                . '/start - Начало работы с ботом' . "\n"
                . '/donate - если желаете оказать помощь сообществу');
        });
    }

    protected function getTelegramUserId()
    {
        $this->bot->onCommand('myid', function (Context $ctx) {
            if ($ctx->getChatType() != 'channel') {
                $ctx->reply($ctx->getUserID());
            }
        });
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
        $this->bot->onCommand('setCommand', function (Context $ctx) {
            $this->bot->ExtentionApi()->setMyCommands(['commands' => [
                [
                    'command' => '/start',
                    'description' => 'Начало работы с ботом'
                ],
                [
                    'command' => '/donate',
                    'description' => 'Материальная помощь сообществу'
                ],
                [
                    'command' => '/tariff',
                    'description' => 'Доступные тарифы'
                ]
            ]]);
            $ctx->reply('Команды зарегистрированы.');
        });
    }

    protected function tariffOnUser()
    {
        $this->bot->onCommand('tariff', function (Context $ctx) {
            if (str_split($ctx->getChatID(), 1)[0] !== '-') {
                $ctx->reply('Доступные тарифы находятся в разделе "Мои подписки".');
            }
        });
    }

    protected function tariffOnChat()
    {
        $this->bot->onCommand('tariff' . $this->bot->botFullName, function (Context $ctx) {
            $community = $this->communityRepo->getCommunityByChatId($ctx->getChatID());
            if ($community) {
                [$text, $menu] = $this->tariffButton($community);
                $ctx->replyHTML($text, $menu);
            } else $ctx->replyHTML('Тарифов нет.');
        });
    }

    private function tariffButton($community, $userId = NULL)
    {
        try {
            $menu = Menux::Create('links')->inline();
            $text = 'Доступные тарифы';
            if ($community->tariff->variants->first() == NULL) {
                return ['Тарифы не установлены для сообщества', ''];

            }
            foreach ($community->tariff->variants as $variant) {
                if ($variant->price !== 0 && $variant->isActive == true) {
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
            }
            return [$text, $menu];
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

    private function inlineQuery($donate)
    {
        $this->bot->onInlineQuery($donate->inline_link, function (Context $ctx) use ($donate) {
            $result = new Result();
            $article = new Article(1);
            $message = new InputTextMessageContent();

            $image = $donate->getMainImage() ? $donate->getMainImage()->url : '';
            $description = $donate->description ? $donate->description : 'Описания нет!';
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
    }

    protected function donateOnChat()
    {
        try {
            $this->bot->onCommand('donate' . $this->bot->botFullName, function (Context $ctx) {
                $community = $this->communityRepo->getCommunityByChatId($ctx->getChatID());
                $donate = $community->donate()->first();

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
                        $description = ($donate->description !== NULL) ? $donate->description : 'Описания нет!';
                        $text = $description . $image;
                        $ctx->replyHTML($text, $menu);
                    } else $ctx->reply('В сообществе не определены донаты');
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
                    $communities = $this->communityRepo->getCommunityBelongsUserId($ctx->getChatID());

                    if ($communities->first()) {
                        $menu = Menux::Create('links')->inline();
                        foreach ($communities as $community) {
                            $menu->row()->btn($community->title, 'variant:' . $community->id);
                        }
                        $ctx->reply('Выберите сообщество, которому желаете оказать материальную помощ.', $menu);
                        $ctx->enter('donate');
                    }
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
                }

                if ($payment && $payment->type == 'tariff' && $payment->status == 'CONFIRMED') {

                    $community = $payment->community;

                    $ty = TelegramUser::where('telegram_id', $ctx->getUserID())->first();

                    if (!$ty->communities()->find($community->id)) {
                        $ty->communities()->attach($community);
                        $this->bot->getExtentionApi()->unKickUser($ctx->getUserID(), $community->connection->chat_id);
                    }

                    $variant = $community->tariff->variants()->find($payment->payable_id);
                    if (!$ty->tariffVariant->find($variant->id)) {
                        foreach ($ty->tariffVariant->where('tariff_id', $community->tariff->id) as $userTariff) {

                            if ($userTariff->id !== $variant->id) {
                                $ty->tariffVariant()->detach($userTariff->id);
                            }
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

                $communities = $this->communityRepo->getCommunityBelongsUserId($ctx->getChatID());

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
                $communities = $this->communityRepo->getCommunityBelongsUserId($ctx->getChatID());
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

    private function subscription()
    {
        try {
            $this->bot->onAction('subscription-{id:string}', function (Context $ctx) {
                $connectionId = $ctx->var('id');
                $menu = Menux::Create('links')->inline();
                $connection = $this->connectionRepo->getConnectionById($connectionId);

                $menu->row()->btn('Получить доступ к ресурсу', 'access-' . $connectionId)
                    ->row()->btn('Продлить подписку', 'extend-' . $connectionId)
                    ->row()->btn('Отписаться', 'unsubscribe-' . $connectionId);

                $user = TelegramUser::where('telegram_id', $ctx->getUserID())->with('tariffVariant')->first();

                $tariffVariant = $connection->community->tariff->variants()->whereHas('payFollowers', function ($q) use ($user) {
                    $q->where('id', $user->id);
                })->first();
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
            });
            $this->access();
            $this->extend();
            $this->unsubscribe();
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

                if ($ty->tariffVariant->find($tariffVariant->id)) {
                    $ty->tariffVariant()->updateExistingPivot($tariffVariant->id, [
                        'isAutoPay' => false
                    ]);
                    if ($connection->telegram_user_id == $ctx->getUserID()) {
                        $this->bot->getExtentionApi()->kickUser($ty->telegram_id, $connection->chat_id);
                        $ty->communities()->detach($tariffVariant->tariff->community->id);
                    }
                    $ctx->reply('Вы успешно отписались.');
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
                if ($connection->chat_invite_link == NULL) {
                    $invite = $this->createAndSaveInviteLink($connection);
                } else $invite = '#';
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
                if ($variant->price !== 0 && $variant->isActive !== false) {
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
}
