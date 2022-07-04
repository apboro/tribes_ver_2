<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\Telegram\MainComponents\Madeline;
use App\Models\TelegramUser;
use App\Models\Community;

class SetNewTelegramUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $chatId;

    public function __construct($chatId)
    {
        $this->chatId = $chatId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $community = Community::whereHas('connection', function ($query) {
            $query->where('chat_id', $this->chatId);
        })->first();

        $pwr_chat = Madeline::settings()->getPwrChat($this->chatId);
        $existUsers = [];
        foreach ($pwr_chat['participants'] as $user) {
            if ($user['role'] !== 'banned' && $user['user']['type'] !== 'bot') {
                $existUsers[] = $user;
            }
        }
        foreach ($existUsers as $user) {

            $ty = TelegramUser::firstOrCreate([
                'telegram_id' => $user['user']['id']
            ]);

            $ty->auth_date   = isset($user['date']) ? $user['date'] : NULL;
            $ty->user_name  = isset($user['user']['username']) ? $user['user']['username'] : NULL;
            $ty->first_name  = isset($user['user']['first_name']) ? $user['user']['first_name'] : NULL;
            $ty->last_name   = isset($user['user']['last_name']) ? $user['user']['last_name'] : NULL;
            $ty->save();

            $ty->communities()->attach($community);
        }
    }
}
