<?php

namespace App\Console\Commands;

use App\Models\TariffVariant;
use App\Models\TelegramUser;
use App\Models\User;
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use App\Services\Tinkoff\Payment as Pay;
use Carbon\Carbon;
use Illuminate\Console\Command;

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
                            //echo "var{$variant->title} \n";
                            if (date('H:i') == $variant->pivot->prompt_time || $variant->period === 0) {
                                echo "Time = {$variant->pivot->prompt_time}". "Period = {$variant->period}".PHP_EOL;
                                $userName = ($user->user_name) ? '<a href="t.me/' . $user->user_name . '">' . $user->user_name . '</a>' : $user->telegram_id;
                                //echo "job for {$variant->title} \n";
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
                                            echo 'found payer!'. PHP_EOL;
//                                            if ($variant->pivot->end_tarif_date < Carbon::now()) {
                                                //echo "create pay {$variant->title} \n";
                                                dump('Oplata tarifa: ', $follower, $variant);
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
                                        echo 'sending logs to chat '. PHP_EOL;
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
                                        echo 'sending message to comminity author '. PHP_EOL;
                                        $this->telegramService->sendMessageFromBot(
                                            config('telegram_bot.bot.botName'),
                                            $payment->community->connection->telegram_user_id,
                                            $message
                                        );

                                        $user->tariffVariant()->updateExistingPivot($variant->id, [
                                            'days' => $variant->period,
//                                            'end_tarif_date' => Carbon::now()->addDays($variant->period)->format('d.m.Y'),
                                            'prompt_time' => date('H:i')
                                        ]);
                                    } else {
                                        //echo "not create payment  \n";
                                        // отключить рекуррентный платеж
                                        if ($variant->pivot->isAutoPay === true) {
                                            $user->tariffVariant()->updateExistingPivot($variant->id, [
                                                'days' => 0,
                                                'isAutoPay' => false
                                            ]);

                                            // $this->telegramService->kickUser(
                                            //     config('telegram_bot.bot.botName'),
                                            //     $user->telegram_id,
                                            //     $variant->tariff->community->connection->chat_id
                                            // );
                                            // $user->communities()->detach($variant->tariff->community->id);

                                            // if ($variant->tariff->tariff_notification == true) {
                                            //     $this->telegramService->sendMessageFromBot(
                                            //         config('telegram_bot.bot.botName'),
                                            //         $variant->tariff->community->connection->telegram_user_id,
                                            //         'Пользователь ' . $userName . ' был забанен в связи с неуплатой тарифа'
                                            //     );
                                            // }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

        } catch (\Exception $e) {
            $this->telegramLogService->sendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
        return 0;
    }
}
