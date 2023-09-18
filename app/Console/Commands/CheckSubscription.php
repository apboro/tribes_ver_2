<?php

namespace App\Console\Commands;

use App\Events\SubscriptionMade;
use App\Jobs\SendEmails;
use App\Models\Subscription;
use App\Models\UserSubscription;
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use App\Services\Tinkoff\Payment as Pay;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
        Log::info('CheckSubscription');
        $userSubscriptions = UserSubscription::all();
        foreach ($userSubscriptions as $userSubscription)
        {
            try {
                if (Carbon::createFromTimestamp($userSubscription->expiration_date) < Carbon::now()) {
                    Log::info('date expiated subscription ' . json_encode($userSubscription, JSON_UNESCAPED_UNICODE));
                    $isPayPlan = $userSubscription->subscription_id === UserSubscription::PAY_PLAN_ID;
                    if ($userSubscription->isRecurrent && $isPayPlan) {
                        $p = new Pay();
                        $p->amount($userSubscription->subscription->price * 100)
                            ->charged(true)
                            ->payFor($userSubscription->subscription)
                            ->payer($userSubscription->user);
                        $payment = $p->pay();

                        if ($payment) {
                            Log::info('Payment for subscription ' . $userSubscription->id . ' success');
                            SubscriptionMade::dispatch($userSubscription->user, $userSubscription->subscription);
                        } else {
//                            SubscriptionMade::dispatch($userSubscription->user, Subscription::find(1));
                            //TODO TBS-1667 add log telegran or email or slack
                            Log::error('payment user subscription error');
                        }
                    } elseif($userSubscription->subscription_id === UserSubscription::TRIAL_PLAN_ID) {
                        $userSubscription->isRecurrent = false;
                        $userSubscription->save();
                    }
                }
            } catch (\Exception $e) {
                //TODO TBS-1667 add log telegran or email or slack
                Log::error($e->getMessage());
            }
        }

        return 0;
    }
}
