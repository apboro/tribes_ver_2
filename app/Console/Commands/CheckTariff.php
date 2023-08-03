<?php

namespace App\Console\Commands;

use App\Models\TariffVariant;
use App\Models\TelegramUser;
use App\Models\User;
use App\Services\SMTP\Mailer;
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use App\Services\Tinkoff\Payment as Pay;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckTariff extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:tariff';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subtract a day in the tariff and check how much is left';
    protected TelegramMainBotService $telegramService;
    private TelegramLogService $telegramLogService;

    /**
     * Create a new command instance.
     *
     * @param TelegramMainBotService $telegramService
     * @param TelegramLogService $telegramLogService
     */
    public function __construct(
        TelegramMainBotService $telegramService,
        TelegramLogService     $telegramLogService
    )
    {
        parent::__construct();
        $this->telegramService = $telegramService;
        $this->telegramLogService = $telegramLogService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $telegramUsers = TelegramUser::with('tariffVariant')->get();
            foreach ($telegramUsers as $user) {
                //echo "user{$user->user_id} \n";
                $follower = User::find($user->user_id);
                if ($follower) {
                    if ($user->tariffVariant->first()) {
                        foreach ($user->tariffVariant as $variant) {
                            /** @var TariffVariant $variant*/
                            if ($variant->price == 0 && $variant->period == $variant->tariff->test_period)
                                continue;
                            if (date('H:i') == $variant->pivot->prompt_time || $variant->period === 0) {
                                $userName = $user->user_name;
                                if ($variant->pivot->days < 1) {
                                    if ($variant->pivot->isAutoPay === true) {
                                        if(false/*todo проверить через бот состоит ли пользователь в группе на данный момент*/){
                                            //если проверка что-то ответила, то обновить параметр exit_date
                                        }
                                        //  если участник покинул группу exit_date IS NOT NULL
                                        //  то переводить подписку в состояние isAutoPay = false, платеж не создавать $payment = NULL;

                                        if ($user->hasLeaveCommunity($variant->tariff->community_id)) {
                                            $payment = NULL;
                                        } elseif ($variant->isActive) {
                                                //echo "create pay {$variant->title} \n";
                                                Log::channel('tinkoff')
                                                    ->info(now() .' Oplata tarifa: follower_id - '. $follower->id .' summa: '.$variant->price * 100 . ' community_id - '. $variant->tariff->community_id);
                                                $p = new Pay();
                                                $p->amount($variant->price * 100)
                                                    ->charged(true)
                                                    ->payFor($variant)
                                                    ->payer($follower);
                                                $payment = $p->pay();
                                                $payId = $payment->id ?? 'undefined';
//                                            }
                                        } else {
                                            //если тариф вариант неактивный, то платеж не создавать
                                            $payment = NULL;
                                        }

                                    } else {
                                        $payment = NULL;
                                    }
                                    if ($payment) {
                                        $lastName = $user->last_name ?? '';
                                        $firstName = $user->first_name ?? '';
                                        $this->telegramService->sendMessageFromBot(
                                            config('telegram_bot.bot.botName'),
                                            env('TELEGRAM_LOG_CHAT'),
                                            "Рекуррентное списание от " . $firstName . $lastName . " в сообщество "
                                        );
                                        //todo
                                        $payerName = $user->publicName() ?? '';
                                        $tariffName = $variant->title ?? '';
                                        $tariffCost = ($payment->amount / 100) ?? 0;
                                        $tariffEndDate = Carbon::now()->addDays($variant->period)->format('d.m.Y') ?? '';
                                        $message = "Участник $payerName оплатил $tariffName в сообществе {$payment->community->title},
                                стоимость $tariffCost рублей действует до $tariffEndDate г.";
                                         $this->telegramService->sendMessageFromBot(
                                            config('telegram_bot.bot.botName'),
                                            $payment->community->connection->telegram_user_id,
                                            $message
                                        );

                                        $user->tariffVariant()->updateExistingPivot($variant->id, [
                                            'days' => $variant->period,
                                            'recurrent_attempt' => 0,
                                            'prompt_time' => date('H:i')
                                        ]);
                                        Log::channel('tinkoff')->info('Next payment in days -'. $variant->period . ' at '. date('H:i'));
                                    } else {
                                        $tariff_variant_name = $variant->title;
                                        $community_name = $variant->community()->title;

                                        if ($variant->pivot->recurrent_attempt >= 3) {
                                             $this->telegramService->kickUser(
                                                 config('telegram_bot.bot.botName'),
                                                 $user->telegram_id,
                                                 $variant->tariff->community->connection->chat_id
                                             );
                                             $user->communities()->detach($variant->tariff->community->id);


                                             if ($variant->tariff->tariff_notification) {
                                                 $this->telegramService->sendMessageFromBot(
                                                     config('telegram_bot.bot.botName'),
                                                     $variant->tariff->community->connection->telegram_user_id,
                                                     'Пользователь ' . $userName . ' был забанен в связи с неуплатой тарифа'
                                                 );
                                             }
                                            $userTextMessageView = view('mail.kick_user', compact('tariff_variant_name', 'community_name'))->render();
                                            $ownerTextMessageView = view('mail.kick_user', compact('userName','tariff_variant_name', 'community_name'))->render();
                                            new Mailer('Сервис Spodial', $userTextMessageView, 'Вы исключены', $user->user->email);
                                            new Mailer('Сервис Spodial', $ownerTextMessageView, 'Пользователь исключен', $variant->community()->communityOwner->email);

                                        } else {
                                            $message = "Не удалось оплатить $variant->title в сообществе {$variant->tariff->community->title},
                                стоимостью $variant->price руб.";
                                            $this->telegramService->sendMessageFromBot(
                                                config('telegram_bot.bot.botName'),
                                                $user->telegram_id,
                                                $message
                                            );

                                            $user->tariffVariant()->updateExistingPivot($variant->id, [
                                                'days' => 0,
                                            ]);
                                            $variant->pivot->increment('recurrent_attempt');

                                            $userTextMessageView = view('mail.recurrent_bad_attempt', compact('tariff_variant_name', 'community_name'))->render();
                                            $ownerTextMessageView = view('mail.recurrent_bad_attempt', compact('userName','tariff_variant_name', 'community_name'))->render();
                                            new Mailer('Сервис Spodial', $userTextMessageView, 'Оплата не удалась', $user->user->email);
                                            new Mailer('Сервис Spodial', $ownerTextMessageView, 'Оплата не удалась', $variant->community()->communityOwner->email);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

        } catch (\Exception $e) {
            Log::channel('tinkoff')->error($e);
            $this->telegramLogService->sendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
        return 0;
    }
}
