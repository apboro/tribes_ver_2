<?php

namespace App\Console\Commands;

use App\Models\TelegramUser;
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use Illuminate\Console\Command;
use App\Services\Tinkoff\Payment as Pay;
use App\Models\User;

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
        TelegramLogService $telegramLogService
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
                            //echo "var{$variant->title} \n";
                            if (date('H:i') == $variant->pivot->prompt_time || $variant->period === 0) {
                                $userName = ($user->user_name) ? '<a href="t.me/' . $user->user_name . '">' . $user->user_name . '</a>' : $user->telegram_id;
                                //echo "job for {$variant->title} \n";
                                if ($variant->pivot->days < 1) {
                                    if ($variant->pivot->isAutoPay === true) {
                                        //echo "create pay {$variant->title} \n";
                                        $p = new Pay();
                                        $p->amount($variant->price * 100)
                                            ->charged(true)
                                            ->payFor($variant)
                                            ->payer($follower);

                                        $payment = $p->pay();
                                        $payId = $payment->id??'undefined';
                                        //echo "create pay  $payId\n";
                                    } else $payment = NULL;
                                    if ($payment) {
                                        $lastName = $user->last_name ?? '';
                                        $firstName = $user->first_name ?? '';
                                        $this->telegramService->sendMessageFromBot(
                                            config('telegram_bot.bot.botName'),
                                            env('TELEGRAM_LOG_CHAT'),
                                            "Рекуррентное списание от " . $firstName . $lastName . " в сообщетво "
                                        );
                                        $user->tariffVariant()->updateExistingPivot($variant->id, [
                                            'days' => $variant->period,
                                            'prompt_time' => date('H:i')
                                        ]);
                                    } else {
                                        //echo "not create payment  \n";
                                        if ($variant->pivot->isAutoPay === true) {
                                            $user->tariffVariant()->updateExistingPivot($variant->id, [
                                                'days' => 0,
                                                'isAutoPay' => false
                                            ]);

                                            $this->telegramService->kickUser(
                                                config('telegram_bot.bot.botName'),
                                                $user->telegram_id,
                                                $variant->tariff->community->connection->chat_id
                                            );
                                            $user->communities()->detach($variant->tariff->community->id);

                                            if ($variant->tariff->tariff_notification == true) {
                                                $this->telegramService->sendMessageFromBot(
                                                    config('telegram_bot.bot.botName'),
                                                    $variant->tariff->community->connection->telegram_user_id,
                                                    'Пользователь ' . $userName . ' был забанен в связи с неуплатой тарифа'
                                                );
                                            }
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
