<?php

namespace App\Console\Commands;

use App\Jobs\SendEmails;
use App\Models\UserSubscription;
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use App\Services\Tinkoff\Payment as Pay;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:subscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and process subscription payment';

    /**
     * Create a new command instance.
     *
     * @return void
     */
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
        $subscriptions = UserSubscription::all();
        foreach ($subscriptions as $subscription)
        {
            if ($subscription->expiration_date > Carbon::now()
                && $subscription->isRecurrent)
            {
                $p = new Pay();
                $p->amount($subscription->price * 100)
                    ->charged(true)
                    ->payFor($subscription)
                    ->payer($subscription->user);
                $payment = $p->pay();

                if ($payment){
                    $this->telegramService->sendMessageFromBot(
                        config('telegram_bot.bot.botName'),
                        $subscription->user->telegramMeta[0]->telegram_id,
                        'Оплата подписки'
                    );
                }

            }
        }

        return 0;
    }
}
