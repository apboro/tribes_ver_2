<?php

namespace App\Console\Commands;

use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use Illuminate\Console\Command;
use App\Models\Community;
use App\Models\Donate;
use Exception;

class SendDonateMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:donate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'command to send donation message';

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
        TelegramLogService $telegramLogService
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
        try {
            $communities = Community::with('connection')->get();
            foreach ($communities as $community) {
                foreach ($community->donate as $donate) {
                    if ($donate) {
                        if (date('H:i') == $donate->prompt_at_hours . ':' . $donate->prompt_at_minutes) {
                            foreach ($donate->variants as $variants) {
                                if ($variants->price && $variants->isActive !== false) {
                                    $key = array_search($variants->currency, Donate::$currency);
                                    $currencyLabel = Donate::$currency_labels[$key];
                                    $data = [
                                        'amount' => $variants->price,
                                        'currency' => $variants->currency,
                                        'donateId' => $donate->id
                                    ];
                                    $desc = $variants->description ? ' — ' . $variants->description : '';
                                    $sumDonate[] = [[
                                        'text' => $variants->price . $currencyLabel . $desc,
                                        "url" => $community->getDonatePaymentLink($data)
                                    ]];
                                } elseif ($variants->min_price && $variants->max_price && $variants->isActive !== false) {
                                    $dataNull = [
                                        'amount' => 0,
                                        'currency' => 0,
                                        'donateId' => $donate->id
                                    ];
                                    $desc = $variants->description ?? 'Произвольная сумма';
                                    $sumDonate[] = [[
                                        'text' => $desc,
                                        "url" => $community->getDonatePaymentLink($dataNull)
                                    ]];
                                }
                            }

                            $desc = $donate->description ?? '';
                            $image = $donate->getMainImage() ? '<a href="' . route('main') . $donate->getMainImage()->url . '">&#160</a>' : '';
                            $text = $desc . $image;
                            $chat_id = $community->connection->chat_id ?? '';
                            if(isset($sumDonate))
                                $this->telegramService->sendMessageFromBot(config('telegram_bot.bot.botName'), $chat_id, $text, false, $sumDonate);
                        }
                    }
                }
            }
            return 0;
        } catch (\Exception $e) {
            $this->telegramLogService->sendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }
}
