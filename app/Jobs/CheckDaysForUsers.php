<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Models\TariffVariant;
use App\Models\User;

use App\Services\TelegramLogService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Services\TelegramMainBotService;
use Exception;

class CheckDaysForUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $telegramUserId;
    protected TelegramLogService $telegramLogService;
    protected $sendMessage;

    /**
     * Create a new job instance.
     *
     * @param $telegramUserId
     * @param TelegramLogService $telegramLogService
     */
    public function __construct($telegramUserId, $sendMessage, TelegramLogService $telegramLogService)
    {
        $this->sendMessage = $sendMessage;
        $this->telegramLogService = $telegramLogService;
        $this->telegramUserId = $telegramUserId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->telegramLogService->sendLogMessage( "Выполнение планировщика");
        try {
            $tv = TariffVariant::whereHas('payFollowers', function ($q) {
                $q->where('telegram_id', $this->telegramUserId);
            })->with('payFollowers')->get();
            foreach ($tv as $variant) {

                if ($variant->payFollowers->first()->pivot->days == 1) {

                    $message = ($variant->tariff->reminder_description) ? $variant->tariff->reminder_description : '';

                    $image = ($variant->tariff->getReminderImage()) ? '<a href="' . route('main') . $variant->tariff->getReminderImage()->url . '">&#160</a>' : '';
                    $text = $image . $message;

                    foreach ($variant->tariff->variants as $variantForButton) {
                        $data = [
                            'amount' => $variantForButton->price,
                            'currency' => 0,
                            'type' => 'tariff',
                            'telegram_user_id' => NULL
                        ];
                        if ($variantForButton->price !== 0 && $variantForButton->isActive == true) {
                            $button[] = [[
                                'text' => (string)$variantForButton->title . ' — ' . $variantForButton->price . '₽',
                                "url" => (string)$variant->tariff->community->getTariffPaymentLink($data)
                            ]];
                        }
                    }

//                     $this->sendMessage->sendMess($this->telegramUserId, $text, true, $button ?? []);
//
//                     $firstName = ($variant->payFollowers->first()->first_name) ? '<a href="t.me/' . $variant->payFollowers->first()->first_name . '">'
//                         . $variant->payFollowers->first()->first_name . '</a>' : '';
//
//                    $this->sendMessage->sendMess(
//                         $variant->tariff->community->connection->telegram_user_id,
//                         'Пользователю ' . $firstName . ' осталось менее 1 дня до окончания тарифа'
//                     );
                }
            }
        } catch (Exception $e) {
            $this->telegramLogService->sendLogMessage('Где-то ошибка! Проверь лучше.');

        }
    }
}
