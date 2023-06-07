<?php

namespace App\Jobs;

use App\Models\TelegramMessage;
use App\Repositories\Community\CommunityRepository;
use App\Repositories\Knowledge\KnowledgeRepository;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\Telegram\TelegramConnectionRepository;
use App\Services\Knowledge\ManageQuestionService;
use App\Services\Telegram\MainBotCollection;
use App\Services\Telegram\MainComponents\MainBotCommands;
use App\Services\Telegram\MainComponents\TelegramMidlwares;
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteTelegramMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $chat_id;
    private int $message_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($chat_id, $message_id)
    {
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
        $bot = new TelegramMainBotService(app(MainBotCollection::class),
            new MainBotCommands(new TelegramConnectionRepository(), new CommunityRepository(),
                new PaymentRepository(new TelegramLogService(app(MainBotCollection::class))),
                new KnowledgeRepository(), new ManageQuestionService(new KnowledgeRepository())),
            new TelegramMidlwares(), new TelegramLogService(app(MainBotCollection::class)));

        $bot->deleteUserMessage(env('TELEGRAM_BOT_NAME'), $this->message_id, $this->chat_id);
        TelegramMessage::where('group_chat_id', $this->chat_id)->where('message_id', $this->message_id)->delete();
    }
}
