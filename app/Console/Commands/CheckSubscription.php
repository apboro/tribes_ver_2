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
use App\Services\Pay\PayService;

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


    private function reSubscription(UserSubscription $userSubscription)
    {
        if ($userSubscription->canBeRenew()) {
            $payment = PayService::extendSubscription($userSubscription->subscription->price, $userSubscription->subscription, $userSubscription->user);
            if ($payment) {
                Log::info('Payment for subscription ' . $userSubscription->id . ' success');
                SubscriptionMade::dispatch($userSubscription->user, $userSubscription->subscription);

                return true;
            } 
                Log::error('payment user subscription error');              
        } 
        $userSubscription->deactivate();

        return false;
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userSubscriptions = UserSubscription::findActiveExpiredSubscriptions();
        foreach ($userSubscriptions as $userSubscription) {
            $this->reSubscription($userSubscription);
        }

        return 0;
    }
}
