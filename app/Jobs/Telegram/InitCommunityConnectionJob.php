<?php

namespace App\Jobs\Telegram;

use App\Models\TelegramUser;
use App\Models\User;
use App\Services\Telegram;
use Askoldex\Teletant\Context;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Writer\Ods\Content;

class InitCommunityConnectionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $type;
    private array $data;

    /**
     * Create a new job instance.
     *
     * @return void
     * @throws \JsonException
     */
    public function __construct(string $type, string $data)
    {
        $this->type = $type;
        $this->data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = ''; //  create new or find by tg id

        Log::info('telegram connection init');
        Log::info('context:' . $this->data[TelegramUser::TELEGRAM_ID]);
        $tgUserId = $this->data[TelegramUser::TELEGRAM_ID];

        $user = User::findByTelegramUserId($tgUserId);

        if (!$user) {
            log::error(' __________  not user _________');
            exit;
        }

        $service = app()->make(Telegram::class);
        /** @var Telegram $service */

        $result = $service->invokeCommunityConnect($user, $this->type, $tgUserId);
        if ($result['original']['status'] === 'error') {
            Log::error('telegram_account_not_connected');
        }else{
            Log::error('connection status ' . $result['original']['status']);
        }

//        TelegramUser::provideOneUser($this->data);
//
//        $tgUser = TelegramUser::where('telegram_id', '=', $tgUserId)->first();
//
//        if($tgUser) {
//            $user = $tgUser->user;
//            Log::info('find User:' . $user->id);
//        }else{
//            $password = $tgUserId . '@' . $tgUserId . '.loc';
//            $user = User::easyRegister($password);
////            Log::info('not User: register new user id: ' . $user->id );
//            Log::info('not User: register new user id: ' );
//        }



    }
}
