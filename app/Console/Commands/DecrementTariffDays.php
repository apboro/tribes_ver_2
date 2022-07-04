<?php

namespace App\Console\Commands;

use App\Models\TelegramUser;
use App\Models\User;
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

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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
            $follower = User::find($user->user_id);
            $this->decrement($user);
            $this->decrement($follower);
        }
    }

    private function decrement($user)
    {
        if ($user->tariffVariant->first() !== NULL) {
            foreach ($user->tariffVariant as $variant) {
                if($variant->pivot->days and $variant->pivot->days !== 0){
                    $user->tariffVariant()->updateExistingPivot($variant->id, [
                        'days' => $variant->pivot->days - 1
                    ]);
                }
            }
        }
    }
}
