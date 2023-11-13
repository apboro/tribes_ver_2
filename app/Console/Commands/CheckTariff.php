<?php

namespace App\Console\Commands;

use App\Models\{
    TariffVariant,
    TelegramUser,
    User,
    TelegramUserTariffVariant
};
use App\Services\SMTP\Mailer;
use App\Services\{
    TelegramLogService,
    TelegramMainBotService
};
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
    ) {
        parent::__construct();
        $this->telegramService = $telegramService;
        $this->telegramLogService = $telegramLogService;
    }


    private function removeFromCommunity(TelegramUserTariffVariant $buyedTariff): void
    {
        $this->telegramService->kickUser(
            config('telegram_bot.bot.botName'),
            $buyedTariff->telegramUser->telegram_id,
            $buyedTariff->tariffVariant->tariff->community->connection->chat_id
        );

        $buyedTariff->telegramUser->communities()->updateExistingPivot($buyedTariff->tariffVariant->tariff->community_id, [
            'excluded' => true,
            'exit_date' => time()
        ]);

        $buyedTariff->telegramUser->tariffVariant()->detach($buyedTariff->tarif_variants_id);
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $buyedTariffs = TelegramUserTariffVariant::findEndPaids();

            foreach ($buyedTariffs as $buyedTariff) {
                $telegramUser = $buyedTariff->telegramUser;
                $tariffVariant = $buyedTariff->tariffVariant;
                $tariff = $tariffVariant->tariff;

                // Пользователя нет в сообществе, он покинул сообщество или тариф не активен
                if (!$telegramUser->getCommunityById($tariff->community_id) ||
                            $telegramUser->hasLeaveCommunity($tariff->community_id) || 
                            !$tariffVariant->isActive) {
                    $this->removeFromCommunity($buyedTariff);
                    continue;
                }

                // Оплатить
                $follower = User::find($telegramUser->user_id);
                $payment = (new Pay())->amount($tariffVariant->price * 100)
                    ->charged(true)
                    ->payFor($tariffVariant)
                    ->payer($follower)
                    ->pay();

                if ($payment) {
                    Log::debug('Прошла рекурентная оплата за продление тарифа', [$payment]);
                    // Обновляем срок оплаченного тарифа 
                    $telegramUser->tariffVariant()->updateExistingPivot($tariffVariant->id, [
                        'days' => $tariffVariant->period,
                        'recurrent_attempt' => 0,
                        'prompt_time' => date('H:i')
                    ]);

                    $tariffName = $tariffVariant->title ?? '';
                    $tariffCost = ($payment->amount / 100) ?? 0;

                    // Сообщение владельцу сообщества
                    $tariffEndDate = Carbon::now()->addDays($tariffVariant->period)->format('d.m.Y') ?? '';
                    $message = "Участник " . $telegramUser->publicName() . " оплатил тариф \"$tariffName\" в сообществе {$payment->community->title}, стоимость $tariffCost рублей, действует до $tariffEndDate.";
                    $this->telegramService->sendMessageFromChatBot(
                        $payment->community->connection->telegram_user_id,
                        $message
                    );

                    // Сообщение в ЛОГ
                    $this->telegramService->sendMessageFromChatBot(
                        env('TELEGRAM_LOG_CHAT'),
                        "Рекуррентное списание $tariffCost рублей от " . $telegramUser->publicName() . " в сообщество \"$tariffName\"."
                    );
                } else {
                    // Не удалось оплатить - логика 3х попыток оплаты
                    $recurrentAttempt = $buyedTariff->recurrent_attempt + 1;
                    $telegramUser->tariffVariant()->updateExistingPivot($tariffVariant->id, [
                        'days' => 0,
                        'recurrent_attempt' => $recurrentAttempt
                    ]);

                    if ($recurrentAttempt < 3) {
                        $message = "Не удалось оплатить $tariffVariant->title в сообществе {$tariff->community->title}, стоимость $tariffVariant->price руб.";
                        $this->telegramService->sendMessageFromChatBot(
                            $telegramUser->telegram_id,
                            $message
                        );
                    } else {
                        $this->removeFromCommunity($buyedTariff);
                        if ($tariff->tariff_notification) {
                            $this->telegramService->sendMessageFromChatBot(
                                $tariff->community->connection->telegram_user_id,
                                'Пользователь ' . $telegramUser->publicName() . ' был забанен в сообществе "' . $tariff->community->title . '" в связи с неуплатой тарифа'
                            );
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
