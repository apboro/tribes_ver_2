<?php

namespace App\Console\Commands;

use App\Helper\PseudoCrypt;
use App\Models\TariffVariant;
use App\Models\TelegramUser;
use App\Models\User;
use App\Services\TelegramMainBotService;
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

    public function handle()
    {
        try {
            $telegramUsers = TelegramUser::with('tariffVariant')->get();
            foreach ($telegramUsers as $user) {
                if ($user->tariffVariant->first()) {
                    foreach ($user->tariffVariant as $variant) {

                        if ($variant->price !== 0 && $variant->period !== $variant->tariff->test_period)
                            continue;

                        if (date('H:i') == $variant->pivot->prompt_time) {

                            $userName = ($user->user_name) ? '<a href="t.me/' . $user->user_name . '">' . $user->user_name . '</a>' : $user->telegram_id;

                            if ($variant->pivot->days = 1) {
                                $communityTitle = $variant->tariff->community->title ?? '';
                                $this->telegramService->sendMessageFromBot(config('telegram_bot.bot.botName'), $user->telegram_id,
                                 'Пробный период в сообществе' . $communityTitle . ' подходит к концу. Оплатите подписку: <a href="' .
                                  route('community.tariff.payment', ['hash' => PseudoCrypt::hash($variant->tariff->community->id, 8)])  . '">Ссылка</a>', true, []);
                            }

                            $follower = User::find($user->user_id);

                            $tariffVariant = TariffVariant::where('tariff_id', $variant->tariff->id)->whereHas('payUsers', function ($q) use($follower) {
                                $q->where('id', $follower->id);
                            })->first();

                            if ($variant->pivot->days < 1 and $tariffVariant !== NULL) {

                                // $this->telegramService->kickUser(config('telegram_bot.bot.botName'), $user->telegram_id, $variant->tariff->community->connection->chat_id);
                                // $user->communities()->detach($variant->tariff->community->id);

                                // if ($variant->tariff->tariff_notification == true) {
                                //     $this->telegramService->sendMessageFromBot(config('telegram_bot.bot.botName'),
                                //         $variant->tariff->community->connection->telegram_user_id,
                                //         'Пользователь ' . $userName . ' был забанен в связи с неуплатой тарифа', false, []
                                //     );
                                // }
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $this->telegramService->sendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }
}
