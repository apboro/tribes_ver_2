<?php

namespace App\Http\Controllers;

use App\Helper\PseudoCrypt;
use App\Jobs\SetNewTelegramUsers;
use App\Models\Community;
use App\Models\Course;
use App\Models\Donate;
use App\Models\DonateVariant;
use App\Models\Payment;
use App\Services\TelegramLogService;
use App\Services\TelegramMainBotService;
use Illuminate\Http\Request;
use App\Models\Tariff;
use App\Models\TariffVariant;
use App\Models\TelegramUser;
use App\Models\TestData;
use App\Models\TelegramConnection;
use App\Models\Statistic;
use App\Models\User;
use App\Models\UserIp;
use App\Models\Lesson;
use App\Models\TelegramMessage;
use App\Models\TelegramMessageReaction;
use App\Models\Template;
use App\Models\Video;
use App\Models\Text;
use App\Repositories\Telegram\TeleMessageReactionRepositoryContract;
use App\Repositories\Telegram\TeleMessageRepositoryContract;
use App\Repositories\Telegram\TelePostReactionRepositoryContract;
use App\Repositories\Telegram\TelePostRepositoryContract;
use App\Repositories\Telegram\TelePostViewsReposirotyContract;
use App\Repositories\Video\VideoRepository;
use App\Services\SMS16;
use App\Services\Telegram;
use App\Services\Telegram\MainBotCollection;
use Illuminate\Support\Facades\Http;

use App\Services\Tinkoff\Payment as Pay;
use App\Services\WebcasterPro;
use Illuminate\Support\Facades\Auth;
use App\Services\Telegram\MainComponents\Madeline;
use App\Services\Telegram\TelegramMtproto\UserBot;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\SMS16 as SmsService;

use DateTime;
use Exception;

class TestBotController extends Controller
{

    public function index(Request $request)
    {
        $sms = new SmsService;
        $balance = $sms->getBalance();
        dd($balance['money']);
        // $params = [
        //     'api_key' => 'efd6bf962dbedbb09e247232b4b56924',
        //     "events" => [
        //         "user_id" => "john_doe@gmail.com",
        //         "event_type" => "watch_tutorial",
        //         "country" => "United States",
        //         "ip" => "127.0.0.1"
        //     ]
        // ];
        // $req = Http::post('https://api2.amplitude.com/2/httpapi', $params);
        // dd($req);

    }
}
