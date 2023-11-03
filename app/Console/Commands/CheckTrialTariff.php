<?php

namespace App\Console\Commands;

use App\Helper\PseudoCrypt;
use App\Jobs\SendEmails;
use App\Models\{TariffVariant,
                TelegramUserTariffVariant,
                TelegramUser,
                User,
                Tariff};
use App\Services\TelegramMainBotService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckTrialTariff extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:trial';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subtract a day in the tariff and check how much is left';

    protected TelegramMainBotService $telegramService;

    /**
     * Create a new command instance.
     *
     * @param TelegramMainBotService $telegramService
     */
    public function __construct(TelegramMainBotService $telegramService)
    {
        $this->telegramService = $telegramService;
        parent::__construct();
    }

    private function endingTrialMessage(TelegramUserTariffVariant $trial): string
    {
        $link = 'https://t.me/' . str_replace('@', '', config('telegram_bot.bot.botFullName')) . '?start=tariff-' . $trial->tariffVariant->tariff->inline_link . '_' . $trial->tariffVariant->tariff->getVariantByPaidType(true)->inline_link;
        $tariffEndDate = Carbon::now()->addDays($trial->tariffVariant->period)->format('d.m.Y H:i') ?? '';
        $communityTitle = strip_tags($trial->tariffVariant->tariff->community->title) ?? '';

        return "Пробный период в сообществе $communityTitle подходит к концу." . "\n" .
            "Срок окончания пробного периода: $tariffEndDate." . "\n" .
            "Для продления доступа Вы можете оплатить тариф: <a href='$link'>Ссылка</a>";
    }

    public function handle()
    {
        try {
            // Этап 1 - пользователи, у которых закончился тестовый период
            $endedTrials = TelegramUserTariffVariant::findEndedTrials();

            foreach ($endedTrials as $trial) {
                $trial->finish();

                if (!$trial->isTariffPayed()) {
                    // Убираем из сообщества
                    $this->telegramService->kickUser(
                        config('telegram_bot.bot.botName'),
                        $trial->telegramUser->telegram_id,
                        $trial->tariffVariant->tariff->community->connection->chat_id
                    );
                    $trial->telegramUser->communities()->updateExistingPivot($trial->tariffVariant->tariff->community_id, [
                        'excluded' => true,
                        'exit_date' => time()
                    ]);

                    // Сообщение о блокировке пользователя
                    if ($trial->tariffVariant->tariff->tariff_notification) {
                        $this->telegramService->sendMessageFromChatBot(
                            $trial->tariffVariant->tariff->community->connection->telegram_user_id,
                            'Пользователь ' . $trial->telegramUser->user_name . ' был забанен в связи с неуплатой тарифа',
                        );
                    }
                }
            }

            // Этап 2 - пользователи, у которых остался 1 день тестового периода
            $endingTrials = TelegramUserTariffVariant::findEndingTrials();
            foreach ($endingTrials as $trial) {
                if (!$trial->isTariffPayed()) {      
                    $this->telegramService->sendMessageFromChatBot($trial->telegramUser->telegram_id, $this->endingTrialMessage($trial));
                }
            }
        } catch (\Exception $e) {
            $this->telegramService->sendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }
}
