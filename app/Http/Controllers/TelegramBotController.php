<?php

namespace App\Http\Controllers;

use App\Exceptions\TelegramException;
use App\Models\TestData;
use App\Services\TelegramMainBotService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class TelegramBotController extends Controller
{
    protected TelegramMainBotService $mainBotService;

    public function __construct(TelegramMainBotService $mainBotService)
    {
        $this->mainBotService = $mainBotService;
    }

    public function index(Request $request)
    {
        $data = $request->collect();
        TestData::create([
            'data' => $data
        ]);
        $botName = config('telegram_bot.bot.botName');
        if(env('GRAB_TEST_DATA') === true) {
            $time = time();
            Storage::disk('telegram_data')->put("message_{$botName}_{$time}.json",$data);
        }

        $this->mainBotService->run($botName, $request->collect());
    }

    //hook for second register bot
    public function indexBot2(Request $request)
    {
        $data = $request->collect();
        TestData::create([
            'data' => $data
        ]);
        $botName = config('telegram_bot.bot1.botName');
         if(env('GRAB_TEST_DATA') === true) {
             $time = time();

             Storage::disk('telegram_data')->put("message_{$botName}_{$time}.json",$data);
         }

         $this->mainBotService->run($botName, $request->collect());
    }
}
