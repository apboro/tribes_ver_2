<?php

namespace App\Http\Controllers;

use App\Helper\PseudoCrypt;
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
use App\Models\Template;
use App\Models\Video;
use App\Models\Text;
use App\Repositories\Video\VideoRepository;
use App\Services\SMS16;
use App\Services\Telegram;
use App\Services\Telegram\MainBotCollection;
use Illuminate\Support\Facades\Http;

use App\Services\Tinkoff\Payment as Pay;
use App\Services\WebcasterPro;
use Illuminate\Support\Facades\Auth;
use App\Services\Telegram\MainComponents\Madeline;
use App\Services\Telegram\TelegramApi\Mtproto;
use Carbon\Carbon;

use DateTime;


class TestBotController extends Controller
{

    public function index(Request $request)
    {
        // another id = 1510955178 or 666997162
        // kanal id = 1504673809 access_hash = 6334485774387705507
        // webstyle id = 738071830
        $mtproto = new Mtproto();
        $auth = $mtproto->getMessages(1, '+79194393154', 'channel', 1504673809, '6334485774387705507');
        dd($auth);
    }

}
