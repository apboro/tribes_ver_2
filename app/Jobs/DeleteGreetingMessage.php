<?php

namespace App\Jobs;

use App\Models\TelegramMessage;
use App\Services\TelegramMainBotService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteGreetingMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $chat_id;
    private int $message_id;
    protected TelegramMainBotService $botService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($botService, $chat_id, $message_id)
    {
        $this->botService = $botService;
        $this->chat_id = $chat_id;
        $this->message_id = $message_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->botService->deleteUserMessage(env('TELEGRAM_BOT_NAME'), $this->message_id, $this->chat_id);
        TelegramMessage::where('group_chat_id', $this->chat_id)->where('message_id', $this->message_id)->delete();
    }
}
