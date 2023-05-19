<?php

namespace App\Jobs;

use App\Domain\Scripts\BehaviorRules\IncomingRule;
use App\Models\CommunityRule;
use App\Services\Telegram\Extention\ExtentionApi;
use App\Services\TelegramMainBotService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BehaviorIncomeRuleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private CommunityRule $communityRule;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CommunityRule $communityRule)
    {
        $this->communityRule = $communityRule;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(TelegramMainBotService $botService)
    {
        $telegramApi = $botService->getApiCommandsForBot(config('telegram_bot.bot.botName'));
        $domain = new IncomingRule($telegramApi);
        $domain($this->communityRule);
    }
}
