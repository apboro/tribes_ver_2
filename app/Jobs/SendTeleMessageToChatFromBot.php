<?php

namespace App\Jobs;

use App\Services\Telegram\BotInterface\TelegramMainBotServiceContract;
use App\Services\TelegramMainBotService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Log\Logger;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendTeleMessageToChatFromBot implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $chatId;
    private $text;
    private $botName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($botName,$chatId,$text)
    {
        $this->chatId = $chatId;
        $this->text = $text;
        $this->botName = $botName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(TelegramMainBotService $botService)
    {
        $botService->sendMessageFromBot($this->botName, $this->chatId, $this->text);
    }

}
