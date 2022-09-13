<?php

namespace App\Console\Commands;

use App\Models\TelegramUser;
use App\Models\User;
use App\Services\TelegramLogService;
use Illuminate\Console\Command;

class DecrementTariffDays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tariff:decrement';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    private TelegramLogService $telegramLogService;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TelegramLogService $telegramLogService)
    {
        parent::__construct();
        $this->telegramLogService = $telegramLogService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $telegramUsers = TelegramUser::with('tariffVariant')->get();
        foreach ($telegramUsers as $user) {
            $this->decrement($user);
        }
    }

    private function decrement($user)
    {
        try {
            if ($user->tariffVariant->first() !== NULL) {
                foreach ($user->tariffVariant as $variant) {
                    if ($variant->pivot->days and $variant->pivot->days !== 0) {
                        $user->tariffVariant()->updateExistingPivot($variant->id, [
                            'days' => $variant->pivot->days - 1
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            $this->telegramLogService->sendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }
}
